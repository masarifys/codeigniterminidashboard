<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceCancellationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cancellation_type' => [
                'type' => 'ENUM',
                'constraint' => ['immediate', 'end_of_billing_period'],
                'default' => 'end_of_billing_period',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'cancelled'],
                'default' => 'pending',
            ],
            'requested_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('service_id');
        $this->forge->addKey('user_id');
        $this->forge->createTable('service_cancellations');
    }

    public function down()
    {
        $this->forge->dropTable('service_cancellations');
    }
}
