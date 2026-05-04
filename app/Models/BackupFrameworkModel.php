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
        'origen',
        'db_nombre',
        'notas',
        'estado',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithCliente(int $clienteId = null, string $fecha = null, int $perPage = 20)
    {
        $this->select('backups_frameworks.*, clientes_frameworks.nombre as cliente_nombre')
             ->join('clientes_frameworks', 'clientes_frameworks.id = backups_frameworks.cliente_id')
             ->orderBy('backups_frameworks.fecha', 'DESC');

        if ($clienteId) {
            $this->where('backups_frameworks.cliente_id', $clienteId);
        }

        if ($fecha) {
            $this->where('DATE(backups_frameworks.fecha)', $fecha);
        }

        return $this->paginate($perPage);
    }

    public function getUltimosPorCliente()
    {
        return $this->select('backups_frameworks.*, clientes_frameworks.nombre as cliente_nombre')
            ->join('clientes_frameworks', 'clientes_frameworks.id = backups_frameworks.cliente_id')
            ->orderBy('backups_frameworks.fecha', 'DESC')
            ->groupBy('backups_frameworks.cliente_id')
            ->findAll();
    }

    public function existeHash(string $hash): bool
    {
        return $this->where('hash', $hash)->first() !== null;
    }
}
