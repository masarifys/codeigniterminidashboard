<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }
        return view('auth/login');
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('username', $username)->first();

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Username not found');
            }

            if (!password_verify($password, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect password');
            }

            if ($user['is_active'] != 1) {
                return redirect()->back()->withInput()->with('error', 'Account is inactive');
            }

            // Set session
            $sessionData = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/client/dashboard');
            }
        }

        // Show login form
        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'confirm_password' => 'required|matches[password]',
                'full_name' => 'required|min_length[3]|max_length[100]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', ['validation' => $this->validator]);
            }

            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'full_name' => $this->request->getPost('full_name'),
                'role' => 'client'
            ];

            if ($this->userModel->insert($data)) {
                session()->setFlashdata('success', 'Registration successful. Please login.');
                return redirect()->to('/auth/login');
            } else {
                session()->setFlashdata('error', 'Registration failed. Please try again.');
            }
        }

        return view('auth/register');
    }

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email'
            ];

            if (!$this->validate($rules)) {
                return view('auth/forgot_password', ['validation' => $this->validator]);
            }

            $email = $this->request->getPost('email');
            $user = $this->userModel->getUserByEmail($email);

            if ($user) {
                $token = $this->userModel->generateResetToken($email);
                
                if ($token) {
                    // Send email
                    $resetLink = base_url('auth/reset-password/' . $token);
                    $this->sendResetEmail($email, $user['full_name'], $resetLink);
                    
                    session()->setFlashdata('success', 'Password reset link has been sent to your email.');
                } else {
                    session()->setFlashdata('error', 'Failed to generate reset token. Please try again.');
                }
            } else {
                // Don't reveal if email exists or not for security
                session()->setFlashdata('success', 'If the email exists, a password reset link has been sent.');
            }

            return redirect()->to('/auth/forgot-password');
        }

        return view('auth/forgot_password');
    }

    public function resetPassword($token = null)
    {
        if (!$token) {
            session()->setFlashdata('error', 'Invalid reset token.');
            return redirect()->to('/auth/login');
        }

        $user = $this->userModel->getUserByResetToken($token);
        if (!$user) {
            session()->setFlashdata('error', 'Invalid or expired reset token.');
            return redirect()->to('/auth/login');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'password' => 'required|min_length[8]',
                'confirm_password' => 'required|matches[password]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/reset_password', ['validation' => $this->validator, 'token' => $token]);
            }

            $newPassword = $this->request->getPost('password');
            
            if ($this->userModel->resetPassword($token, $newPassword)) {
                session()->setFlashdata('success', 'Password has been reset successfully. Please login with your new password.');
                return redirect()->to('/auth/login');
            } else {
                session()->setFlashdata('error', 'Failed to reset password. Please try again.');
                return view('auth/reset_password', ['token' => $token]);
            }
        }

        return view('auth/reset_password', ['token' => $token, 'user' => $user]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }

    private function redirectToDashboard()
    {
        $role = session()->get('role');
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/client/dashboard');
        }
    }
    
    private function sendResetEmail($email, $fullName, $resetLink)
{
    try {
        $gmailOAuth = new \App\Libraries\GmailOAuthService();
        
        // Check if Gmail OAuth is authorized
        if (!$gmailOAuth->isAuthorized()) {
            log_message('error', 'Gmail OAuth not authorized. Cannot send reset email.');
            return false;
        }
        
        // Load email template
        $message = view('emails/password_reset', [
            'full_name' => $fullName,
            'reset_link' => $resetLink
        ]);
        
        // Send email via Gmail OAuth
        $result = $gmailOAuth->sendEmail(
            $email,
            'Password Reset Request - Exputra Billing',
            $message,
            null,
            'Exputra Billing'
        );
        
        if ($result['success']) {
            log_message('info', 'Password reset email sent via Gmail OAuth to: ' . $email . ', Message ID: ' . $result['message_id']);
            return true;
        } else {
            log_message('error', 'Gmail OAuth failed to send email: ' . $result['error']);
            return false;
        }
        
    } catch (\Exception $e) {
        log_message('error', 'Gmail OAuth exception: ' . $e->getMessage());
        return false;
    }
}
}