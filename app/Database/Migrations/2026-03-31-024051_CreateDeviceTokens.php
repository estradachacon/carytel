<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeviceTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'token' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true, // opcional (por si luego lo ligás a usuario)
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

        // 🔥 evita duplicados del mismo dispositivo
        $this->forge->addUniqueKey('token');

        $this->forge->createTable('device_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('device_tokens');
    }
}