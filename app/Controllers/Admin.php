<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClientModel;
use App\Models\ServiceModel;
use App\Models\ServicePackageModel;
use App\Models\ReminderModel;
use App\Models\InvoiceModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    protected $userModel;
    protected $clientModel;
    protected $serviceModel;
    protected $servicePackageModel;
    protected $reminderModel;
    protected $invoiceModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->clientModel = new ClientModel();
        $this->serviceModel = new ServiceModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->reminderModel = new ReminderModel();
        $this->invoiceModel = new InvoiceModel();
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

    // Client Management
    public function clients()
    {
        $data = [
            'title' => 'Client Management',
            'clients' => $this->clientModel->getClientsWithUser()
        ];

        return view('admin/clients', $data);
    }

    public function clientDetail($id)
    {
        $client = $this->clientModel->getClientWithUser($id);
        
        if (!$client) {
            session()->setFlashdata('error', 'Client not found');
            return redirect()->to('/admin/clients');
        }

        $services = $this->serviceModel->where('user_id', $client['user_id'])->findAll();
        $invoices = $this->invoiceModel->where('user_id', $client['user_id'])->findAll();

        $data = [
            'title' => 'Client Detail - ' . $client['business_name'],
            'client' => $client,
            'services' => $services,
            'invoices' => $invoices
        ];

        return view('admin/client_detail', $data);
    }

    public function createClient()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'user_id' => 'required|integer',
                'business_name' => 'required|min_length[3]|max_length[255]',
                'contact_email' => 'permit_empty|valid_email',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'user_id' => $this->request->getPost('user_id'),
                'business_name' => $this->request->getPost('business_name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'contact_email' => $this->request->getPost('contact_email'),
                'contact_phone' => $this->request->getPost('contact_phone'),
                'domain' => $this->request->getPost('domain'),
                'status' => $this->request->getPost('status'),
                'notes' => $this->request->getPost('notes'),
            ];

            if ($this->clientModel->insert($data)) {
                session()->setFlashdata('success', 'Client created successfully');
                return redirect()->to('/admin/clients');
            } else {
                session()->setFlashdata('error', 'Failed to create client');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => 'Create Client',
            'users' => $this->userModel->where('role', 'client')->findAll()
        ];

        return view('admin/client_form', $data);
    }

    public function editClient($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            session()->setFlashdata('error', 'Client not found');
            return redirect()->to('/admin/clients');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'business_name' => 'required|min_length[3]|max_length[255]',
                'contact_email' => 'permit_empty|valid_email',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'business_name' => $this->request->getPost('business_name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'contact_email' => $this->request->getPost('contact_email'),
                'contact_phone' => $this->request->getPost('contact_phone'),
                'domain' => $this->request->getPost('domain'),
                'status' => $this->request->getPost('status'),
                'notes' => $this->request->getPost('notes'),
            ];

            if ($this->clientModel->update($id, $data)) {
                session()->setFlashdata('success', 'Client updated successfully');
                return redirect()->to('/admin/client/' . $id);
            } else {
                session()->setFlashdata('error', 'Failed to update client');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => 'Edit Client',
            'client' => $client,
            'users' => $this->userModel->where('role', 'client')->findAll()
        ];

        return view('admin/client_form', $data);
    }

    public function deleteClient($id)
    {
        if ($this->clientModel->delete($id)) {
            session()->setFlashdata('success', 'Client deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete client');
        }

        return redirect()->to('/admin/clients');
    }

    // Service Management
    public function services()
    {
        $data = [
            'title' => 'Service Management',
            'services' => $this->serviceModel->select('services.*, users.full_name, users.email')
                                              ->join('users', 'users.id = services.user_id')
                                              ->orderBy('services.created_at', 'DESC')
                                              ->findAll()
        ];

        return view('admin/services', $data);
    }

    public function editService($id)
    {
        $service = $this->serviceModel->find($id);
        
        if (!$service) {
            session()->setFlashdata('error', 'Service not found');
            return redirect()->to('/admin/services');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'product_name' => $this->request->getPost('product_name'),
                'domain' => $this->request->getPost('domain'),
                'price' => $this->request->getPost('price'),
                'billing_cycle' => $this->request->getPost('billing_cycle'),
                'registration_date' => $this->request->getPost('registration_date'),
                'due_date' => $this->request->getPost('due_date'),
                'ip_address' => $this->request->getPost('ip_address'),
                'status' => $this->request->getPost('status'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'server' => $this->request->getPost('server'),
                'panel_url' => $this->request->getPost('panel_url'),
                'registrar' => $this->request->getPost('registrar'),
                'domain_expiry_date' => $this->request->getPost('domain_expiry_date'),
                'hosting_provider' => $this->request->getPost('hosting_provider'),
                'hosting_renewal_date' => $this->request->getPost('hosting_renewal_date'),
                'ssl_status' => $this->request->getPost('ssl_status'),
                'ssl_expiry_date' => $this->request->getPost('ssl_expiry_date'),
                'uptime_monitor_url' => $this->request->getPost('uptime_monitor_url'),
            ];

            if ($this->serviceModel->update($id, $data)) {
                session()->setFlashdata('success', 'Service updated successfully');
                return redirect()->to('/admin/services');
            } else {
                session()->setFlashdata('error', 'Failed to update service');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => 'Edit Service',
            'service' => $service,
            'users' => $this->userModel->where('role', 'client')->findAll()
        ];

        return view('admin/service_form', $data);
    }

    // Service Package Management
    public function packages()
    {
        $data = [
            'title' => 'Service Package Management',
            'packages' => $this->servicePackageModel->findAll()
        ];

        return view('admin/packages', $data);
    }

    public function createPackage()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'storage' => $this->request->getPost('storage'),
                'bandwidth' => $this->request->getPost('bandwidth'),
                'price' => $this->request->getPost('price'),
                'billing_cycle' => $this->request->getPost('billing_cycle'),
                'features' => $this->request->getPost('features'),
                'notes' => $this->request->getPost('notes'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            if ($this->servicePackageModel->insert($data)) {
                session()->setFlashdata('success', 'Package created successfully');
                return redirect()->to('/admin/packages');
            } else {
                session()->setFlashdata('error', 'Failed to create package');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => 'Create Service Package'
        ];

        return view('admin/package_form', $data);
    }

    public function editPackage($id)
    {
        $package = $this->servicePackageModel->find($id);
        
        if (!$package) {
            session()->setFlashdata('error', 'Package not found');
            return redirect()->to('/admin/packages');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'storage' => $this->request->getPost('storage'),
                'bandwidth' => $this->request->getPost('bandwidth'),
                'price' => $this->request->getPost('price'),
                'billing_cycle' => $this->request->getPost('billing_cycle'),
                'features' => $this->request->getPost('features'),
                'notes' => $this->request->getPost('notes'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            if ($this->servicePackageModel->update($id, $data)) {
                session()->setFlashdata('success', 'Package updated successfully');
                return redirect()->to('/admin/packages');
            } else {
                session()->setFlashdata('error', 'Failed to update package');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => 'Edit Service Package',
            'package' => $package
        ];

        return view('admin/package_form', $data);
    }

    public function deletePackage($id)
    {
        if ($this->servicePackageModel->delete($id)) {
            session()->setFlashdata('success', 'Package deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete package');
        }

        return redirect()->to('/admin/packages');
    }

    // Monitoring Dashboard
    public function monitoring()
    {
        $services = $this->serviceModel->select('services.*, users.full_name, users.email')
                                       ->join('users', 'users.id = services.user_id')
                                       ->where('services.uptime_monitor_url IS NOT NULL')
                                       ->orderBy('services.uptime_status', 'ASC')
                                       ->findAll();

        $data = [
            'title' => 'Website Monitoring',
            'services' => $services,
            'downCount' => count(array_filter($services, fn($s) => $s['uptime_status'] === 'down')),
            'upCount' => count(array_filter($services, fn($s) => $s['uptime_status'] === 'up')),
        ];

        return view('admin/monitoring', $data);
    }

    // Billing Management
    public function billing()
    {
        $invoices = $this->invoiceModel->select('invoices.*, users.full_name, users.email')
                                       ->join('users', 'users.id = invoices.user_id')
                                       ->orderBy('invoices.due_date', 'DESC')
                                       ->findAll();

        $data = [
            'title' => 'Billing Management',
            'invoices' => $invoices,
            'unpaidCount' => count(array_filter($invoices, fn($i) => $i['status'] === 'unpaid')),
            'paidCount' => count(array_filter($invoices, fn($i) => $i['status'] === 'paid')),
            'pastDueCount' => count(array_filter($invoices, fn($i) => $i['status'] === 'past_due')),
        ];

        return view('admin/billing', $data);
    }

    public function createInvoice()
    {
        if ($this->request->getMethod() === 'POST') {
            // Generate invoice number with timestamp to avoid race conditions
            $timestamp = time();
            $count = $this->invoiceModel->countAll() + 1;
            
            $data = [
                'user_id' => $this->request->getPost('user_id'),
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT) . '-' . $timestamp,
                'service_id' => $this->request->getPost('service_id'),
                'amount' => $this->request->getPost('amount'),
                'due_date' => $this->request->getPost('due_date'),
                'status' => 'unpaid',
            ];

            if ($this->invoiceModel->insert($data)) {
                session()->setFlashdata('success', 'Invoice created successfully');
                return redirect()->to('/admin/billing');
            } else {
                session()->setFlashdata('error', 'Failed to create invoice');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => 'Create Invoice',
            'users' => $this->userModel->where('role', 'client')->findAll(),
            'services' => $this->serviceModel->findAll()
        ];

        return view('admin/invoice_form', $data);
    }
}