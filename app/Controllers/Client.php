<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Client extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
        
        // Check if user is logged in and is client
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'client') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access Denied');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Client Dashboard',
            'user' => $this->userModel->find(session()->get('id'))
        ];

        return view('client/dashboard', $data);
    }

    public function profile()
    {
        $userId = session()->get('id');
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => "required|valid_email|is_unique[users.email,id,$userId]"
            ];

            if (!$this->validate($rules)) {
                return view('client/profile', [
                    'validation' => $this->validator,
                    'user' => $this->userModel->find($userId)
                ]);
            }

            $data = [
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email')
            ];

            if ($this->userModel->update($userId, $data)) {
                // Update session data
                session()->set([
                    'full_name' => $data['full_name'],
                    'email' => $data['email']
                ]);
                session()->setFlashdata('success', 'Profile updated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to update profile');
            }

            return redirect()->to('/client/profile');
        }

        $data = [
            'title' => 'My Profile',
            'user' => $this->userModel->find($userId)
        ];

        return view('client/profile', $data);
    }
}