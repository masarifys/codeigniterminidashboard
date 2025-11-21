<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientsTable extends Migration
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
            'business_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'contact_person' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'contact_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'contact_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'domain' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['progress', 'revision', 'completed', 'cancelled'],
                'default' => 'progress',
            ],
            'notes' => [
                'type' => 'TEXT',
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
        $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}
