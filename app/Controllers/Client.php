<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\InvoiceModel;
use App\Models\TicketModel;
use CodeIgniter\Controller;

class Client extends Controller
{
    protected $userModel;
    protected $serviceModel;
    protected $invoiceModel;
    protected $ticketModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->serviceModel = new ServiceModel();
        $this->invoiceModel = new InvoiceModel();
        $this->ticketModel = new TicketModel();
        helper(['form', 'url']);
        
        // Check if user is logged in and is client
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'client') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access Denied');
        }
    }

    public function dashboard()
    {
        $userId = session()->get('id');
        
        // Get statistics
        $activeServices = $this->serviceModel->where('user_id', $userId)
                                              ->where('status', 'active')
                                              ->countAllResults();
        
        $unpaidInvoices = $this->invoiceModel->where('user_id', $userId)
                                              ->where('status', 'unpaid')
                                              ->countAllResults();
        
        $pastDueInvoices = $this->invoiceModel->where('user_id', $userId)
                                               ->where('status', 'past_due')
                                               ->countAllResults();
        
        $openTickets = $this->ticketModel->where('user_id', $userId)
                                          ->whereIn('status', ['open', 'customer_reply'])
                                          ->countAllResults();
        
        // Get recent invoices (limit 5)
        $recentInvoices = $this->invoiceModel->where('user_id', $userId)
                                              ->orderBy('due_date', 'DESC')
                                              ->limit(5)
                                              ->findAll();
        
        $data = [
            'title' => 'Client Dashboard',
            'user' => $this->userModel->find($userId),
            'activeServices' => $activeServices,
            'unpaidInvoices' => $unpaidInvoices,
            'pastDueInvoices' => $pastDueInvoices,
            'openTickets' => $openTickets,
            'recentInvoices' => $recentInvoices
        ];

        return view('client/dashboard', $data);
    }
    
    public function services()
    {
        $userId = session()->get('id');
        
        // Get all services for the user
        $services = $this->serviceModel->where('user_id', $userId)
                                       ->orderBy('due_date', 'DESC')
                                       ->findAll();
        
        $data = [
            'title' => 'List all products & services',
            'user' => $this->userModel->find($userId),
            'services' => $services
        ];

        return view('client/services', $data);
    }
    
    public function invoices()
    {
        $userId = session()->get('id');
        
        // Get all invoices for the user
        $invoices = $this->invoiceModel->where('user_id', $userId)
                                       ->orderBy('due_date', 'DESC')
                                       ->findAll();
        
        $data = [
            'title' => 'Invoices',
            'user' => $this->userModel->find($userId),
            'invoices' => $invoices
        ];

        return view('client/invoices', $data);
    }
    
    public function support()
    {
        $data = [
            'title' => 'Live Support',
            'user' => $this->userModel->find(session()->get('id'))
        ];

        return view('client/support', $data);
    }
    
    public function tickets()
    {
        $userId = session()->get('id');
        
        // Get all tickets for the user
        $tickets = $this->ticketModel->where('user_id', $userId)
                                     ->orderBy('created_at', 'DESC')
                                     ->findAll();
        
        $data = [
            'title' => 'Trouble Tickets',
            'user' => $this->userModel->find($userId),
            'tickets' => $tickets
        ];

        return view('client/tickets', $data);
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