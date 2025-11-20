<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InvoiceItemsSeeder extends Seeder
{
    public function run()
    {
        // Get existing invoices
        $invoiceModel = new \App\Models\InvoiceModel();
        $invoices = $invoiceModel->findAll();
        
        if (empty($invoices)) {
            echo "No invoices found. Please run TestDataSeeder first.\n";
            return;
        }
        
        $invoiceItemModel = new \App\Models\InvoiceItemModel();
        $transactionModel = new \App\Models\TransactionModel();
        
        // Add items for each invoice
        foreach ($invoices as $invoice) {
            // Create invoice items
            if ($invoice['invoice_number'] == 'INV-2025-0001') {
                // Unlimited L - Annual hosting
                $invoiceItemModel->insert([
                    'invoice_id' => $invoice['id'],
                    'description' => 'Unlimited L Hosting - Annual Subscription',
                    'amount' => 1680000.00
                ]);
            } elseif ($invoice['invoice_number'] == 'INV-2025-0002') {
                // VPS Standard - Monthly
                $invoiceItemModel->insert([
                    'invoice_id' => $invoice['id'],
                    'description' => 'VPS Standard - Monthly Fee',
                    'amount' => 250000.00
                ]);
            } elseif ($invoice['invoice_number'] == 'INV-2025-0003') {
                // Shared Hosting - Quarterly (PAID)
                $invoiceItemModel->insert([
                    'invoice_id' => $invoice['id'],
                    'description' => 'Shared Hosting - Quarterly Payment',
                    'amount' => 150000.00
                ]);
                
                // Add transaction record for paid invoice
                $transactionModel->insert([
                    'invoice_id' => $invoice['id'],
                    'user_id' => $invoice['user_id'],
                    'transaction_id' => 'TRX-2025-' . str_pad($invoice['id'], 6, '0', STR_PAD_LEFT),
                    'gateway' => 'BCA Virtual Account',
                    'amount' => 150000.00,
                    'transaction_date' => '2025-10-14 10:30:00',
                    'status' => 'success'
                ]);
            }
        }
        
        echo "Invoice items and transactions seeded successfully!\n";
    }
}
