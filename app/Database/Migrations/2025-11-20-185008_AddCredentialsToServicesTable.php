<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCredentialsToServicesTable extends Migration
{
    public function up()
    {
        $fields = [
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'server' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'panel_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('services', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('services', ['username', 'password', 'server', 'panel_url']);
    }
}
