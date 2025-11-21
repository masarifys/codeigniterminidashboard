<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminPanelSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        $userModel = new \App\Models\UserModel();
        $adminExists = $userModel->where('role', 'admin')->first();
        
        if (!$adminExists) {
            // WARNING: Change this password immediately in production!
            // Default password is for development/testing only
            $defaultPassword = 'Admin@123!Dev';
            
            $userModel->insert([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash($defaultPassword, PASSWORD_DEFAULT),
                'full_name' => 'Admin User',
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            echo "Admin user created (username: admin, password: $defaultPassword)\n";
            echo "WARNING: Change the admin password immediately!\n";
        }

        // Get test client user
        $testClient = $userModel->where('username', 'testclient')->first();
        
        if ($testClient) {
            // Create client record
            $clientModel = new \App\Models\ClientModel();
            $clientModel->insert([
                'user_id' => $testClient['id'],
                'business_name' => 'Tech Solutions Inc',
                'contact_person' => 'John Doe',
                'contact_email' => 'john@techsolutions.com',
                'contact_phone' => '+62 812-3456-7890',
                'domain' => 'techsolutions.com',
                'status' => 'progress',
                'notes' => 'New website development project. Need to complete design phase by end of month.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            echo "Client record created\n";
        }

        // Create service packages
        $packageModel = new \App\Models\ServicePackageModel();
        
        $packages = [
            [
                'name' => 'Shared Hosting Basic',
                'description' => 'Perfect for small websites and blogs',
                'storage' => '10 GB SSD',
                'bandwidth' => '100 GB',
                'price' => 150000,
                'billing_cycle' => 'monthly',
                'features' => "1 Website\n5 Email Accounts\nFree SSL Certificate\ncPanel Access\n24/7 Support",
                'notes' => 'Entry-level hosting package',
                'is_active' => 1,
            ],
            [
                'name' => 'Shared Hosting Pro',
                'description' => 'For growing businesses',
                'storage' => '50 GB SSD',
                'bandwidth' => '500 GB',
                'price' => 350000,
                'billing_cycle' => 'monthly',
                'features' => "5 Websites\n25 Email Accounts\nFree SSL Certificate\ncPanel Access\n24/7 Priority Support\nDaily Backups",
                'notes' => 'Most popular package',
                'is_active' => 1,
            ],
            [
                'name' => 'VPS Standard',
                'description' => 'Full control with dedicated resources',
                'storage' => '100 GB SSD',
                'bandwidth' => '1000 GB',
                'price' => 500000,
                'billing_cycle' => 'monthly',
                'features' => "4 CPU Cores\n8 GB RAM\n100 GB SSD\nRoot Access\nUnlimited Websites\nFree SSL",
                'notes' => 'For advanced users',
                'is_active' => 1,
            ],
            [
                'name' => 'Cloud Hosting Enterprise',
                'description' => 'Maximum performance and reliability',
                'storage' => 'Unlimited',
                'bandwidth' => 'Unlimited',
                'price' => 2000000,
                'billing_cycle' => 'monthly',
                'features' => "Dedicated Resources\nManaged Service\nDaily Backups\nDDoS Protection\nDedicated Support Team",
                'notes' => 'Enterprise-level solution',
                'is_active' => 1,
            ],
        ];

        foreach ($packages as $package) {
            $package['created_at'] = date('Y-m-d H:i:s');
            $package['updated_at'] = date('Y-m-d H:i:s');
            $packageModel->insert($package);
        }
        echo "Service packages created\n";

        // Update existing services with domain/hosting info
        $serviceModel = new \App\Models\ServiceModel();
        $services = $serviceModel->findAll();
        
        foreach ($services as $service) {
            $serviceModel->update($service['id'], [
                'registrar' => 'Namecheap',
                'domain_expiry_date' => date('Y-m-d', strtotime('+1 year')),
                'hosting_provider' => 'AWS',
                'hosting_renewal_date' => date('Y-m-d', strtotime($service['due_date'])),
                'ssl_status' => 'active',
                'ssl_expiry_date' => date('Y-m-d', strtotime('+90 days')),
                'uptime_monitor_url' => 'https://' . $service['domain'],
                'uptime_status' => 'up',
                'last_uptime_check' => date('Y-m-d H:i:s'),
            ]);
        }
        echo "Services updated with domain/hosting info\n";

        // Create some reminders
        $reminderModel = new \App\Models\ReminderModel();
        
        if ($testClient) {
            $reminders = [
                [
                    'user_id' => $testClient['id'],
                    'service_id' => 1,
                    'type' => 'domain_renewal',
                    'reminder_date' => date('Y-m-d', strtotime('+30 days')),
                    'message' => 'Your domain techsolutions.com will expire in 30 days. Please renew to avoid service disruption.',
                    'is_sent' => 0,
                ],
                [
                    'user_id' => $testClient['id'],
                    'service_id' => 1,
                    'type' => 'ssl_expiry',
                    'reminder_date' => date('Y-m-d', strtotime('+60 days')),
                    'message' => 'Your SSL certificate will expire in 60 days. Renew now for continued security.',
                    'is_sent' => 0,
                ],
            ];

            foreach ($reminders as $reminder) {
                $reminder['created_at'] = date('Y-m-d H:i:s');
                $reminder['updated_at'] = date('Y-m-d H:i:s');
                $reminderModel->insert($reminder);
            }
            echo "Reminders created\n";
        }

        echo "\nAdmin Panel data seeding completed successfully!\n";
        echo "Admin login: username=admin, password=admin123\n";
        echo "Client login: username=testclient, password=password123\n";
    }
}
