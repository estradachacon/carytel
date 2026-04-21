<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackagePaymentsTable extends Migration
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

            'package_id' => [
                'type'     => 'INT',
                'constraint'=> 11,
                'unsigned' => true,
            ],

            'pago_id' => [
                'type'     => 'INT',
                'constraint'=> 11,
                'null'     => true, // opcional por si luego conectas con tabla de pagos
            ],

            'monto_pagado' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],

            'flete_pagado' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],

            // 🔥 ESTADO ANTES DEL PAGO (CLAVE PARA REVERSIÓN)
            'flete_pendiente_antes' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],

            'flete_pagado_antes' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],

            'metodo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20, // caja / cuenta
            ],

            // 🔥 CONTROL DE REVERSIÓN
            'revertido' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],

            'revertido_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('package_id');

        $this->forge->createTable('package_payments');
    }

    public function down()
    {
        $this->forge->dropTable('package_payments');
    }
}