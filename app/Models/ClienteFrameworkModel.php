<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteFrameworkModel extends Model
{
    protected $table            = 'clientes_frameworks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nombre',
        'identificador',
        'api_key',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByIdentificador(string $identificador)
    {
        return $this->where('identificador', $identificador)->first();
    }

    public function getByApiKey(string $apiKey)
    {
        return $this->where('api_key', $apiKey)->first();
    }
}
