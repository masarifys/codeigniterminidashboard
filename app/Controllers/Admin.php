<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
        
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access Denied');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'totalUsers' => $this->userModel->countAll(),
            'totalClients' => $this->userModel->where('role', 'client')->countAllResults(),
            'totalAdmins' => $this->userModel->where('role', 'admin')->countAllResults(),
            'recentUsers' => $this->userModel->orderBy('created_at', 'DESC')->limit(5)->findAll()
        ];

        return view('admin/dashboard', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->findAll()
        ];

        return view('admin/users', $data);
    }

    public function deleteUser($id)
    {
        if ($id == session()->get('id')) {
            session()->setFlashdata('error', 'You cannot delete your own account');
            return redirect()->to('/admin/users');
        }

        if ($this->userModel->delete($id)) {
            session()->setFlashdata('success', 'User deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete user');
        }

        return redirect()->to('/admin/users');
    }

    public function toggleUserStatus($id)
    {
        $user = $this->userModel->find($id);
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $this->userModel->update($id, ['is_active' => $newStatus]);
            session()->setFlashdata('success', 'User status updated successfully');
        }

        return redirect()->to('/admin/users');
    }
}