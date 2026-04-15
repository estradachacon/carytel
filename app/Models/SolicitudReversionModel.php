<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudReversionModel extends Model
{
    protected $table          = 'reversal_requests';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $allowedFields  = [
        'package_id',
        'solicitado_por',
        'estatus',
        'aprobado_por',
        'comentario',
    ];

    public function getSolicitudes()
    {
        return $this->db->table('reversal_requests r')
            ->select('r.*, p.id as package_id, u.user_name as solicitante_nombre')
            ->join('packages p', 'p.id = r.package_id', 'left')
            ->join('users u', 'u.id = r.solicitado_por', 'left')
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getSolicitudDetalle($id)
    {
        return $this->db->table('reversal_requests r')
            ->select('r.*, p.id as package_id, p.monto, 
                    p.estatus as estatus_paquete, 
                    p.estatus2 as estatus2_paquete,
                    u.user_name as solicitante_nombre,
                    a.user_name as aprobador_nombre')
            ->join('packages p', 'p.id = r.package_id', 'left')
            ->join('users u', 'u.id = r.solicitado_por', 'left')
            ->join('users a', 'a.id = r.aprobado_por', 'left')
            ->where('r.id', $id)
            ->get()
            ->getRowArray();
    }
}
