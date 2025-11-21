<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRemindersTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['domain_renewal', 'hosting_renewal', 'ssl_expiry', 'invoice_due', 'maintenance_due'],
                'default' => 'domain_renewal',
            ],
            'reminder_date' => [
                'type' => 'DATE',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'is_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'sent_at' => [
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('service_id');
        $this->forge->createTable('reminders');
    }

    public function down()
    {
        $this->forge->dropTable('reminders');
    }
}
