<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApiKeyToClientes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clientes_frameworks', [
            'api_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'identificador',
            ],
        ]);

        $this->db->query('ALTER TABLE clientes_frameworks ADD UNIQUE INDEX idx_cf_api_key (api_key)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE clientes_frameworks DROP INDEX idx_cf_api_key');
        $this->forge->dropColumn('clientes_frameworks', 'api_key');
    }
}
