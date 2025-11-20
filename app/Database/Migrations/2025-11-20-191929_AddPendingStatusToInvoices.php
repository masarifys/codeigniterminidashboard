<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPendingStatusToInvoices extends Migration
{
    public function up()
    {
        // Modify the status column to include 'pending'
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['unpaid', 'pending', 'paid', 'past_due', 'cancelled'],
                'default' => 'unpaid',
            ],
        ];
        
        $this->forge->modifyColumn('invoices', $fields);
    }

    public function down()
    {
        // Revert back to original enum values
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['unpaid', 'paid', 'past_due', 'cancelled'],
                'default' => 'unpaid',
            ],
        ];
        
        $this->forge->modifyColumn('invoices', $fields);
    }
}
