<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test client user
        $userModel = new \App\Models\UserModel();
        
        $userData = [
            'username' => 'testclient',
            'email' => 'client@test.com',
            'password' => 'password123',
            'full_name' => 'Test Client User',
            'role' => 'client',
            'is_active' => 1
        ];
        
        $userId = $userModel->insert($userData);
        
        // Create test services
        $serviceModel = new \App\Models\ServiceModel();
        
        $services = [
            [
                'user_id' => $userId,
                'product_name' => 'Unlimited L',
                'domain' => 'example.com',
                'price' => 1680000.00,
                'billing_cycle' => 'annually',
                'registration_date' => '2025-11-10',
                'due_date' => '2026-01-09',
                'ip_address' => '192.168.1.100',
                'status' => 'active'
            ],
            [
                'user_id' => $userId,
                'product_name' => 'VPS Standard',
                'domain' => 'subdomain.example.com',
                'price' => 250000.00,
                'billing_cycle' => 'monthly',
                'registration_date' => '2025-10-15',
                'due_date' => '2025-12-15',
                'ip_address' => '192.168.1.101',
                'status' => 'active'
            ],
            [
                'user_id' => $userId,
                'product_name' => 'Shared Hosting',
                'domain' => 'test.example.com',
                'price' => 150000.00,
                'billing_cycle' => 'quarterly',
                'registration_date' => '2025-09-01',
                'due_date' => '2025-12-01',
                'ip_address' => null,
                'status' => 'pending'
            ]
        ];
        
        foreach ($services as $service) {
            $serviceModel->insert($service);
        }
        
        // Create test invoices
        $invoiceModel = new \App\Models\InvoiceModel();
        
        $invoices = [
            [
                'user_id' => $userId,
                'invoice_number' => 'INV-2025-0001',
                'service_id' => 1,
                'amount' => 1680000.00,
                'due_date' => '2025-11-20',
                'paid_date' => null,
                'status' => 'unpaid'
            ],
            [
                'user_id' => $userId,
                'invoice_number' => 'INV-2025-0002',
                'service_id' => 2,
                'amount' => 250000.00,
                'due_date' => '2025-11-15',
                'paid_date' => null,
                'status' => 'past_due'
            ],
            [
                'user_id' => $userId,
                'invoice_number' => 'INV-2025-0003',
                'service_id' => 3,
                'amount' => 150000.00,
                'due_date' => '2025-10-15',
                'paid_date' => '2025-10-14',
                'status' => 'paid'
            ]
        ];
        
        foreach ($invoices as $invoice) {
            $invoiceModel->insert($invoice);
        }
        
        // Create test tickets
        $ticketModel = new \App\Models\TicketModel();
        
        $tickets = [
            [
                'user_id' => $userId,
                'subject' => 'Server not responding',
                'department' => 'Technical Support',
                'priority' => 'high',
                'status' => 'open'
            ],
            [
                'user_id' => $userId,
                'subject' => 'Question about billing',
                'department' => 'Billing',
                'priority' => 'medium',
                'status' => 'answered'
            ],
            [
                'user_id' => $userId,
                'subject' => 'Need help with setup',
                'department' => 'Technical Support',
                'priority' => 'low',
                'status' => 'closed'
            ]
        ];
        
        foreach ($tickets as $ticket) {
            $ticketModel->insert($ticket);
        }
        
        echo "Test data seeded successfully!\n";
        echo "Login credentials:\n";
        echo "Username: testclient\n";
        echo "Password: password123\n";
    }
}
