<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRemuneradoConCuentaToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('packages', [
            'remunerado_con_cuenta' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'metodo_remu',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('packages', 'remunerado_con_cuenta');
    }
}