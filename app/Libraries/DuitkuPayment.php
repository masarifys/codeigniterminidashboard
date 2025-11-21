<?php

namespace App\Libraries;

use Config\Duitku;

class DuitkuPayment
{
    protected $config;
    
    public function __construct()
    {
        $this->config = new Duitku();
    }
    
    /**
     * Generate signature for API request
     * 
     * @param array $data
     * @return string
     */
    public function generateSignature($data)
    {
        $merchantCode = $this->config->merchantCode;
        $merchantOrderId = $data['merchantOrderId'];
        $paymentAmount = (string)intval($data['paymentAmount']); // Force integer to remove decimals
        $apiKey = $this->config->apiKey;
        
        $signature = hash('sha256', $merchantCode . $merchantOrderId . $paymentAmount . $apiKey);
        
        return $signature;
    }
    
    /**
     * Validate callback signature from Duitku
     * 
     * @param array $data
     * @return bool
     */
    public function validateCallback($data)
    {
        if (!isset($data['merchantOrderId']) || !isset($data['amount']) || !isset($data['signature'])) {
            return false;
        }
        
        $merchantCode = $this->config->merchantCode;
        $merchantOrderId = $data['merchantOrderId'];
        $amount = (string)intval($data['amount']); // Force integer to remove decimals
        $apiKey = $this->config->apiKey;
        
        $calculatedSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
        
        return $calculatedSignature === $data['signature'];
    }
    
    /**
     * Get available payment methods from Duitku
     * 
     * @param float $amount
     * @return array|null
     */
    public function getPaymentMethods($amount = 10000)
    {
        $url = $this->config->getBaseUrl() . '/paymentmethod/getpaymentmethod';
        
        $intAmount = intval($amount); // Force integer to remove decimals
        
        $params = [
            'merchantcode' => $this->config->merchantCode,
            'amount' => $intAmount,
            'datetime' => date('Y-m-d H:i:s'),
            'signature' => hash('sha256', $this->config->merchantCode . $intAmount . 'paymentmethod' . $this->config->apiKey)
        ];
        
        $response = $this->sendRequest($url, $params);
        
        if ($response && isset($response['paymentFee'])) {
            return $response['paymentFee'];
        }
        
        return null;
    }
    
    /**
     * Create invoice/payment request to Duitku
     * 
     * @param array $invoiceData
     * @return string|null Payment URL
     */
    public function createInvoice($invoiceData)
    {
        // Validate required fields
        $requiredFields = ['merchantOrderId', 'paymentAmount', 'email', 'productDetails', 'callbackUrl', 'returnUrl'];
        foreach ($requiredFields as $field) {
            if (empty($invoiceData[$field])) {
                log_message('error', "Duitku API Error: Missing required field '{$field}'");
                return null;
            }
        }
        
        // Validate email format
        if (!filter_var($invoiceData['email'], FILTER_VALIDATE_EMAIL)) {
            log_message('error', "Duitku API Error: Invalid email format '{$invoiceData['email']}'");
            return null;
        }
        
        // Validate and convert payment amount to integer
        $originalAmount = $invoiceData['paymentAmount'];
        $amount = intval($originalAmount);
        if ($amount <= 0) {
            log_message('error', 'Duitku: Invalid payment amount - original: ' . $originalAmount . ', converted: ' . $amount);
            return null;
        }
        
        // Fix: Use correct full path for API endpoint
        $url = $this->config->getBaseUrl() . '/v2/inquiry';
        
        $params = [
            'merchantCode' => $this->config->merchantCode,
            'paymentAmount' => (string)$amount, // Use already validated and converted amount
            'paymentMethod' => 'SP', // SP = User selects payment method
            'merchantOrderId' => $invoiceData['merchantOrderId'],
            'productDetails' => $invoiceData['productDetails'],
            'email' => $invoiceData['email'],
            'phoneNumber' => $invoiceData['phoneNumber'] ?? '08123456789',
            'additionalParam' => '',
            'merchantUserInfo' => $invoiceData['merchantUserInfo'] ?? '',
            'customerVaName' => $invoiceData['merchantUserInfo'] ?? 'Customer',
            'callbackUrl' => $invoiceData['callbackUrl'],
            'returnUrl' => $invoiceData['returnUrl'],
            'expiryPeriod' => $invoiceData['expiryPeriod'] ?? 60
        ];
        
        // Generate signature
        $params['signature'] = $this->generateSignature($params);
        
        // Log request for debugging
        log_message('info', 'Duitku API Request to: ' . $url);
        log_message('info', 'Duitku API Request params: ' . json_encode([
            'merchantOrderId' => $params['merchantOrderId'],
            'paymentAmount' => $params['paymentAmount'],
            'email' => $params['email'],
            'paymentMethod' => $params['paymentMethod']
        ]));
        
        $response = $this->sendRequest($url, $params);
        
        if ($response && isset($response['paymentUrl'])) {
            log_message('info', 'Duitku API Success: Payment URL created for order ' . $params['merchantOrderId']);
            return $response['paymentUrl'];
        }
        
        // Log detailed error for debugging
        $errorMsg = 'Unknown error';
        if (is_array($response)) {
            $errorMsg = json_encode($response);
        } elseif (is_string($response)) {
            $errorMsg = $response;
        }
        log_message('error', 'Duitku API Error Response: ' . $errorMsg);
        
        return null;
    }
    
    /**
     * Check transaction status
     * 
     * @param string $merchantOrderId
     * @return string|null
     */
    public function checkTransactionStatus($merchantOrderId)
    {
        $url = $this->config->getBaseUrl() . '/transactionStatus';
        
        $params = [
            'merchantCode' => $this->config->merchantCode,
            'merchantOrderId' => $merchantOrderId,
            'signature' => hash('sha256', $this->config->merchantCode . $merchantOrderId . $this->config->apiKey)
        ];
        
        $response = $this->sendRequest($url, $params);
        
        if ($response && isset($response['statusCode'])) {
            return $response['statusMessage'];
        }
        
        return null;
    }
    
    /**
     * Test Duitku API connection and configuration
     * 
     * @return array Test results
     */
    public function testConnection()
    {
        $results = [
            'config' => [
                'merchantCode' => $this->config->merchantCode,
                'sandboxMode' => $this->config->sandboxMode,
                'baseUrl' => $this->config->getBaseUrl(),
                'apiKeySet' => !empty($this->config->apiKey)
            ],
            'connectivity' => false,
            'signature' => false,
            'errors' => []
        ];
        
        // Test 1: Check if API key is set
        if (empty($this->config->apiKey)) {
            $results['errors'][] = 'API Key is not configured';
            return $results;
        }
        
        // Test 2: Test signature generation
        $testData = [
            'merchantOrderId' => 'TEST-' . time(),
            'paymentAmount' => '10000'
        ];
        try {
            $signature = $this->generateSignature($testData);
            $results['signature'] = !empty($signature);
            $results['testSignature'] = $signature;
        } catch (\Exception $e) {
            $results['errors'][] = 'Signature generation failed: ' . $e->getMessage();
        }
        
        // Test 3: Test API connectivity with payment methods endpoint
        try {
            $methods = $this->getPaymentMethods(10000);
            $results['connectivity'] = is_array($methods) && count($methods) > 0;
            $results['paymentMethodsCount'] = is_array($methods) ? count($methods) : 0;
        } catch (\Exception $e) {
            $results['errors'][] = 'API connectivity test failed: ' . $e->getMessage();
        }
        
        return $results;
    }
    
    /**
     * Send HTTP request to Duitku API
     * 
     * @param string $url
     * @param array $params
     * @return array|null
     */
    protected function sendRequest($url, $params)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Add 30 second timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 second connection timeout
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);
        
        curl_close($ch);
        
        // Log raw response for debugging
        log_message('info', 'Duitku HTTP Response Code: ' . $httpCode);
        log_message('debug', 'Duitku Raw Response: ' . substr($response, 0, 500)); // Log first 500 chars
        
        if ($curlErrno) {
            log_message('error', 'Duitku cURL Error (' . $curlErrno . '): ' . $curlError);
            return null;
        }
        
        if ($httpCode !== 200) {
            log_message('error', 'Duitku HTTP Error ' . $httpCode . ': ' . substr($response, 0, 200));
            return null;
        }
        
        // Decode JSON and handle errors
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Duitku JSON Decode Error: ' . json_last_error_msg());
            return null;
        }
        
        return $decoded;
    }
}
