<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagosAndUpdatePackagePayments extends Migration
{
    public function up()
    {
        // ===============================
        // TABLA pagos
        // ===============================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'seller_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'total_bruto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'total_flete' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'total_neto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'metodo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'cuenta_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
            'anulado' => [
                'type'    => 'TINYINT',
                'default' => 0,
            ],
            'anulado_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'anulado_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('seller_id');
        $this->forge->createTable('pagos');

        // ===============================
        // MODIFICAR package_payments
        // ===============================
        $this->forge->addColumn('package_payments', [
            'seller_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'pago_id',
            ],
            'tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'seller_id',
            ],
            'flete_descontado' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'after'      => 'flete_pendiente_antes',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('pagos');

        $this->forge->dropColumn('package_payments', 'seller_id');
        $this->forge->dropColumn('package_payments', 'tipo');
        $this->forge->dropColumn('package_payments', 'flete_descontado');
    }
}