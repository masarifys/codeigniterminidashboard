<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\InvoiceModel;
use App\Models\TicketModel;
use App\Models\InvoiceItemModel;
use App\Models\TransactionModel;
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

    public function invoiceDetail($id)
    {
        $userId = session()->get('id');
        $invoice = $this->invoiceModel->find($id);
        
        // Check if invoice exists and belongs to logged in user
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/client/invoices')->with('error', 'Invoice not found');
        }
        
        // Get invoice items
        $invoiceItemModel = new InvoiceItemModel();
        $items = $invoiceItemModel->where('invoice_id', $id)->findAll();
        
        // Get transaction details if paid
        $transaction = null;
        if ($invoice['status'] == 'paid') {
            $transactionModel = new TransactionModel();
            $transaction = $transactionModel->where('invoice_id', $id)->first();
        }
        
        $data = [
            'title' => 'Invoice Detail',
            'user' => $this->userModel->find($userId),
            'invoice' => $invoice,
            'items' => $items,
            'transaction' => $transaction
        ];
        
        return view('client/invoice_detail', $data);
    }

    public function payInvoice($id)
    {
        $userId = session()->get('id');
        $invoice = $this->invoiceModel->find($id);
        
        // Validate invoice
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/client/invoices')->with('error', 'Invoice not found');
        }
        
        if ($invoice['status'] == 'paid') {
            return redirect()->to('/client/invoice/' . $id)->with('info', 'Invoice already paid');
        }
        
        // Create payment request to Duitku
        $duitku = new \App\Libraries\DuitkuPayment();
        $user = $this->userModel->find($userId);
        
        $paymentUrl = $duitku->createInvoice([
            'merchantOrderId' => $invoice['invoice_number'],
            'paymentAmount' => $invoice['amount'],
            'email' => $user['email'],
            'phoneNumber' => '08123456789',
            'productDetails' => 'Payment for invoice ' . $invoice['invoice_number'],
            'merchantUserInfo' => $user['full_name'],
            'callbackUrl' => base_url('client/payment/callback'),
            'returnUrl' => base_url('client/invoice/' . $id),
            'expiryPeriod' => 60
        ]);
        
        if ($paymentUrl) {
            return redirect()->to($paymentUrl);
        } else {
            return redirect()->back()->with('error', 'Failed to create payment. Please try again.');
        }
    }

    public function paymentCallback()
    {
        // Get callback data from Duitku
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Log callback for debugging
        log_message('info', 'Duitku Callback Received: ' . json_encode($data));
        
        // Validate signature
        $duitku = new \App\Libraries\DuitkuPayment();
        if (!$duitku->validateCallback($data)) {
            log_message('error', 'Invalid Duitku callback signature');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid signature']);
        }
        
        // Get invoice
        $invoice = $this->invoiceModel->where('invoice_number', $data['merchantOrderId'])->first();
        
        if (!$invoice) {
            log_message('error', 'Invoice not found: ' . $data['merchantOrderId']);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invoice not found']);
        }
        
        // Update invoice status
        if ($data['resultCode'] == '00') { // Success
            $this->invoiceModel->update($invoice['id'], [
                'status' => 'paid',
                'paid_date' => date('Y-m-d H:i:s')
            ]);
            
            // Save transaction details
            $transactionModel = new TransactionModel();
            $transactionModel->insert([
                'invoice_id' => $invoice['id'],
                'user_id' => $invoice['user_id'],
                'transaction_id' => $data['reference'] ?? $data['merchantOrderId'],
                'gateway' => $data['paymentCode'] ?? 'DUITKU',
                'amount' => $data['amount'],
                'transaction_date' => date('Y-m-d H:i:s'),
                'status' => 'success'
            ]);
            
            log_message('info', 'Invoice ' . $invoice['invoice_number'] . ' paid successfully');
        } else {
            log_message('warning', 'Payment failed for invoice ' . $invoice['invoice_number'] . '. Result code: ' . $data['resultCode']);
        }
        
        return $this->response->setJSON(['status' => 'success']);
    }

    public function serviceDetail($id)
    {
        $userId = session()->get('id');
        $service = $this->serviceModel->find($id);
        
        // Check if service exists and belongs to logged in user
        if (!$service || $service['user_id'] != $userId) {
            return redirect()->to('/client/services')->with('error', 'Service not found');
        }
        
        $data = [
            'title' => 'Service Detail - ' . $service['product_name'],
            'user' => $this->userModel->find($userId),
            'service' => $service
        ];
        
        return view('client/service_detail', $data);
    }

    public function upgradeService($id)
    {
        $userId = session()->get('id');
        $service = $this->serviceModel->find($id);
        
        // Check if service exists and belongs to logged in user
        if (!$service || $service['user_id'] != $userId) {
            return redirect()->to('/client/services')->with('error', 'Service not found');
        }
        
        // TODO: Implement upgrade logic
        return redirect()->to('/client/service/' . $id)->with('info', 'Upgrade feature coming soon');
    }

    public function renewService($id)
    {
        $userId = session()->get('id');
        $service = $this->serviceModel->find($id);
        
        // Check if service exists and belongs to logged in user
        if (!$service || $service['user_id'] != $userId) {
            return redirect()->to('/client/services')->with('error', 'Service not found');
        }
        
        // TODO: Implement renew logic - create invoice for renewal
        return redirect()->to('/client/service/' . $id)->with('info', 'Renewal feature coming soon');
    }

    public function cancelService($id)
    {
        $userId = session()->get('id');
        $service = $this->serviceModel->find($id);
        
        // Check if service exists and belongs to logged in user
        if (!$service || $service['user_id'] != $userId) {
            return redirect()->to('/client/services')->with('error', 'Service not found');
        }
        
        // Get cancellation data from POST
        $reason = $this->request->getPost('reason');
        $cancellationType = $this->request->getPost('cancellation_type');
        
        // Insert into service_cancellations table
        $db = \Config\Database::connect();
        $builder = $db->table('service_cancellations');
        
        $data = [
            'service_id' => $id,
            'user_id' => $userId,
            'reason' => $reason,
            'cancellation_type' => $cancellationType,
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $builder->insert($data);
        
        return redirect()->to('/client/service/' . $id)->with('success', 'Cancellation request submitted successfully');
    }
}