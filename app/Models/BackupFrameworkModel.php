<?php

namespace App\Models;

use CodeIgniter\Model;

class BackupFrameworkModel extends Model
{
    protected $table            = 'backups_frameworks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'cliente_id',
        'archivo',
        'peso',
        'fecha',
        'hash',
        'ip',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 🔹 Obtener backups con nombre de cliente
    public function getWithCliente()
    {
        return $this->select('backups.*, clientes_frameworks.nombre as cliente')
            ->join('clientes_frameworks', 'clientes_frameworks.id = backups.cliente_id')
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    // 🔹 Obtener últimos backups por cliente (útil para dashboard)
    public function getUltimosPorCliente()
    {
        return $this->select('backups.*, clientes_frameworks.nombre as cliente')
            ->join('clientes_frameworks', 'clientes_frameworks.id = backups.cliente_id')
            ->orderBy('fecha', 'DESC')
            ->groupBy('cliente_id')
            ->findAll();
    }

    // 🔹 Verificar si ya existe un backup por hash (evitar duplicados)
    public function existeHash(string $hash)
    {
        return $this->where('hash', $hash)->first() !== null;
    }
}