<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDomainHostingInfoToServices extends Migration
{
    public function up()
    {
        $fields = [
            'registrar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'panel_url',
            ],
            'domain_expiry_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'registrar',
            ],
            'hosting_provider' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'domain_expiry_date',
            ],
            'hosting_renewal_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'hosting_provider',
            ],
            'ssl_status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'expiring_soon'],
                'default' => 'inactive',
                'after' => 'hosting_renewal_date',
            ],
            'ssl_expiry_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'ssl_status',
            ],
            'uptime_monitor_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'after' => 'ssl_expiry_date',
            ],
            'uptime_status' => [
                'type' => 'ENUM',
                'constraint' => ['up', 'down', 'unknown'],
                'default' => 'unknown',
                'after' => 'uptime_monitor_url',
            ],
            'last_uptime_check' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'uptime_status',
            ],
        ];
        
        $this->forge->addColumn('services', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('services', [
            'registrar',
            'domain_expiry_date',
            'hosting_provider',
            'hosting_renewal_date',
            'ssl_status',
            'ssl_expiry_date',
            'uptime_monitor_url',
            'uptime_status',
            'last_uptime_check',
        ]);
    }
}
