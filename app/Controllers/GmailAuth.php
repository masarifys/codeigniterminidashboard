<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class GmailAuth extends Controller
{
    protected $gmailOAuth;

    public function __construct()
    {
        $this->gmailOAuth = new \App\Libraries\GmailOAuthService();
    }

    /**
     * Show authorization status
     */
    public function index()
    {
        $data = [
            'title' => 'Gmail OAuth Setup',
            'isAuthorized' => $this->gmailOAuth->isAuthorized(),
            'authUrl' => $this->gmailOAuth->getAuthorizationUrl()
        ];

        return view('admin/gmail_setup', $data);
    }

    /**
     * Redirect to Google OAuth
     */
    public function authorize()
    {
        $authUrl = $this->gmailOAuth->getAuthorizationUrl();
        return redirect()->to($authUrl);
    }

    /**
     * Handle OAuth callback
     */
    public function callback()
    {
        $code = $this->request->getGet('code');
        $error = $this->request->getGet('error');

        if ($error) {
            session()->setFlashdata('error', 'Authorization denied: ' . $error);
            return redirect()->to('/admin/gmail-setup');
        }

        if ($code) {
            try {
                $tokenData = $this->gmailOAuth->getAccessToken($code);
                session()->setFlashdata('success', 'Gmail OAuth authorization successful! You can now send emails.');
                log_message('info', 'Gmail OAuth authorized successfully');
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Authorization failed: ' . $e->getMessage());
                log_message('error', 'Gmail OAuth authorization failed: ' . $e->getMessage());
            }
        } else {
            session()->setFlashdata('error', 'No authorization code received');
        }

        return redirect()->to('/admin/gmail-setup');
    }

    /**
     * Test email sending
     */
    public function testEmail()
    {
        try {
            $result = $this->gmailOAuth->sendEmail(
                config('Email')->fromEmail, // Send to self for testing
                'Gmail OAuth Test - ' . date('Y-m-d H:i:s'),
                '<h2>🎉 Gmail OAuth Test Successful!</h2>
                <p>Jika Anda menerima email ini, Gmail OAuth sudah bekerja dengan baik!</p>
                <p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</p>
                <hr>
                <p><small>Dikirim dari Mini Dashboard System</small></p>',
                null,
                'Mini Dashboard Test'
            );

            if ($result['success']) {
                session()->setFlashdata('success', 'Test email berhasil dikirim! Message ID: ' . $result['message_id']);
            } else {
                session()->setFlashdata('error', 'Test email gagal: ' . $result['error']);
            }

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Test email exception: ' . $e->getMessage());
        }

        return redirect()->to('/admin/gmail-setup');
    }

    /**
     * Revoke authorization
     */
    public function revoke()
    {
        try {
            $this->gmailOAuth->revokeAuthorization();
            session()->setFlashdata('success', 'Gmail authorization revoked successfully');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to revoke authorization: ' . $e->getMessage());
        }

        return redirect()->to('/admin/gmail-setup');
    }
}