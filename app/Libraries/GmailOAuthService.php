<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;

class GmailOAuthService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tokenPath;

    public function __construct()
    {
        $emailConfig = config('Email');
        $this->clientId = $emailConfig->googleClientId;
        $this->clientSecret = $emailConfig->googleClientSecret;
        $this->redirectUri = $emailConfig->googleRedirectUri;
        $this->tokenPath = WRITEPATH . 'gmail-oauth-token.json';
    }

    /**
     * Generate authorization URL
     */
    public function getAuthorizationUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'https://www.googleapis.com/auth/gmail.send',
            'response_type' => 'code',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token
     */
    public function getAccessToken($authCode)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code',
                'code' => $authCode
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $tokenData = json_decode($response, true);
            $this->saveToken($tokenData);
            return $tokenData;
        } else {
            throw new \Exception('Failed to get access token: ' . $response);
        }
    }

    /**
     * Refresh access token
     */
    public function refreshAccessToken()
    {
        $tokenData = $this->getStoredToken();
        
        if (!isset($tokenData['refresh_token'])) {
            throw new \Exception('No refresh token available');
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $tokenData['refresh_token'],
                'grant_type' => 'refresh_token'
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $newTokenData = json_decode($response, true);
            
            // Keep the refresh token
            if (isset($tokenData['refresh_token'])) {
                $newTokenData['refresh_token'] = $tokenData['refresh_token'];
            }
            
            $this->saveToken($newTokenData);
            return $newTokenData;
        } else {
            throw new \Exception('Failed to refresh token: ' . $response);
        }
    }

    /**
     * Get valid access token (refresh if needed)
     */
    public function getValidAccessToken()
    {
        $tokenData = $this->getStoredToken();
        
        if (!$tokenData) {
            throw new \Exception('No token found. Please authorize first.');
        }

        // Check if token is expired
        if (isset($tokenData['expires_at']) && time() >= $tokenData['expires_at']) {
            // Token expired, refresh it
            try {
                $tokenData = $this->refreshAccessToken();
            } catch (\Exception $e) {
                throw new \Exception('Token expired and refresh failed: ' . $e->getMessage());
            }
        }

        return $tokenData['access_token'];
    }

    /**
     * Send email using Gmail API
     */
    public function sendEmail($to, $subject, $htmlBody, $fromEmail = null, $fromName = 'Mini Dashboard')
    {
        try {
            $accessToken = $this->getValidAccessToken();
            $fromEmail = $fromEmail ?: config('Email')->fromEmail;
            
            // Create email message
            $message = $this->createEmailMessage($to, $subject, $htmlBody, $fromEmail, $fromName);
            
            // Send via Gmail API
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://gmail.googleapis.com/gmail/v1/users/me/messages/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode(['raw' => $message]),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode === 200) {
                $result = json_decode($response, true);
                log_message('info', 'Gmail OAuth email sent successfully. Message ID: ' . $result['id']);
                
                return [
                    'success' => true,
                    'message_id' => $result['id']
                ];
            } else {
                throw new \Exception('Gmail API error: ' . $response);
            }

        } catch (\Exception $e) {
            log_message('error', 'Gmail OAuth send failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create email message in Gmail API format
     */
    private function createEmailMessage($to, $subject, $htmlBody, $fromEmail, $fromName)
    {
        $headers = [
            "From: {$fromName} <{$fromEmail}>",
            "To: {$to}",
            "Subject: {$subject}",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=utf-8"
        ];

        $email = implode("\r\n", $headers) . "\r\n\r\n" . $htmlBody;
        
        return rtrim(strtr(base64_encode($email), '+/', '-_'), '=');
    }

    /**
     * Save token to file
     */
    private function saveToken($tokenData)
    {
        // Add expiration timestamp
        if (isset($tokenData['expires_in'])) {
            $tokenData['expires_at'] = time() + $tokenData['expires_in'] - 60; // 60 seconds buffer
        }

        file_put_contents($this->tokenPath, json_encode($tokenData, JSON_PRETTY_PRINT));
    }

    /**
     * Get stored token
     */
    private function getStoredToken()
    {
        if (file_exists($this->tokenPath)) {
            return json_decode(file_get_contents($this->tokenPath), true);
        }
        return null;
    }

    /**
     * Check if authorized
     */
    public function isAuthorized()
    {
        $tokenData = $this->getStoredToken();
        return $tokenData !== null && isset($tokenData['access_token']);
    }

    /**
     * Revoke authorization
     */
    public function revokeAuthorization()
    {
        if (file_exists($this->tokenPath)) {
            unlink($this->tokenPath);
        }
    }
}