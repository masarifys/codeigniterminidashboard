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
        $paymentAmount = $data['paymentAmount'];
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
        $amount = $data['amount'];
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
        
        $params = [
            'merchantcode' => $this->config->merchantCode,
            'amount' => $amount,
            'datetime' => date('Y-m-d H:i:s'),
            'signature' => hash('sha256', $this->config->merchantCode . $amount . 'paymentmethod' . $this->config->apiKey)
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
        $url = $this->config->getBaseUrl() . '/v2/inquiry';
        
        $params = [
            'merchantCode' => $this->config->merchantCode,
            'paymentAmount' => $invoiceData['paymentAmount'],
            'paymentMethod' => 'VC', // Virtual Account (default)
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
        
        $response = $this->sendRequest($url, $params);
        
        if ($response && isset($response['paymentUrl'])) {
            return $response['paymentUrl'];
        }
        
        // Log error for debugging
        log_message('error', 'Duitku API Error: ' . json_encode($response));
        
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
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            log_message('error', 'Duitku cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            log_message('error', 'Duitku HTTP Error: ' . $httpCode . ' - ' . $response);
            return null;
        }
        
        return json_decode($response, true);
    }
}
