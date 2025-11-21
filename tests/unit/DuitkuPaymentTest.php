<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\DuitkuPayment;

/**
 * Test DuitkuPayment Library
 * 
 * @internal
 */
final class DuitkuPaymentTest extends CIUnitTestCase
{
    protected $duitku;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->duitku = new DuitkuPayment();
    }
    
    /**
     * Test that generateSignature handles integer amounts correctly
     */
    public function testGenerateSignatureWithIntegerAmount(): void
    {
        $data = [
            'merchantOrderId' => 'TEST-123',
            'paymentAmount' => '10000'
        ];
        
        $signature = $this->duitku->generateSignature($data);
        
        $this->assertIsString($signature);
        $this->assertEquals(64, strlen($signature)); // SHA256 produces 64 char hex string
    }
    
    /**
     * Test that generateSignature handles float amounts by converting to integer
     */
    public function testGenerateSignatureWithFloatAmount(): void
    {
        $data = [
            'merchantOrderId' => 'TEST-123',
            'paymentAmount' => '10000.00'
        ];
        
        $signature1 = $this->duitku->generateSignature($data);
        
        // Should produce same signature as integer amount
        $data2 = [
            'merchantOrderId' => 'TEST-123',
            'paymentAmount' => '10000'
        ];
        
        $signature2 = $this->duitku->generateSignature($data2);
        
        $this->assertEquals($signature2, $signature1, 
            'Signature should be same for 10000.00 and 10000');
    }
    
    /**
     * Test that generateSignature handles decimal amounts correctly
     */
    public function testGenerateSignatureWithDecimalAmount(): void
    {
        // Amount with decimals should be truncated to integer
        $data = [
            'merchantOrderId' => 'TEST-123',
            'paymentAmount' => '1680000.50'
        ];
        
        $signature1 = $this->duitku->generateSignature($data);
        
        // Should produce same signature as integer amount (truncated)
        $data2 = [
            'merchantOrderId' => 'TEST-123',
            'paymentAmount' => '1680000'
        ];
        
        $signature2 = $this->duitku->generateSignature($data2);
        
        $this->assertEquals($signature2, $signature1,
            'Signature should be same for 1680000.50 and 1680000 (truncated)');
    }
    
    /**
     * Test that validateCallback works correctly
     */
    public function testValidateCallbackWithValidData(): void
    {
        // This test verifies the callback validation logic
        // Using config values through reflection to avoid hardcoding
        $config = $this->getConfigProperty($this->duitku);
        $merchantCode = $config->merchantCode;
        $apiKey = $config->apiKey;
        
        $merchantOrderId = 'TEST-123';
        $amount = '10000';
        
        $data = [
            'merchantOrderId' => $merchantOrderId,
            'amount' => $amount,
            'signature' => md5($merchantCode . $amount . $merchantOrderId . $apiKey)
        ];
        
        $result = $this->duitku->validateCallback($data);
        
        $this->assertTrue($result);
    }
    
    /**
     * Helper method to access config property for testing
     */
    private function getConfigProperty($object)
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty('config');
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }
    
    /**
     * Test that validateCallback fails with invalid signature
     */
    public function testValidateCallbackWithInvalidSignature(): void
    {
        $data = [
            'merchantOrderId' => 'TEST-123',
            'amount' => '10000',
            'signature' => 'invalid_signature'
        ];
        
        $result = $this->duitku->validateCallback($data);
        
        $this->assertFalse($result);
    }
    
    /**
     * Test that validateCallback fails with missing fields
     */
    public function testValidateCallbackWithMissingFields(): void
    {
        $data = [
            'merchantOrderId' => 'TEST-123'
        ];
        
        $result = $this->duitku->validateCallback($data);
        
        $this->assertFalse($result);
    }
}
