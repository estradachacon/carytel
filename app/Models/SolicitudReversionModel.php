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
        // Traer la solicitud base
        $solicitud = $this->db->table('reversal_requests r')
            ->select('r.*, p.id as package_id, p.monto,
                p.estatus as estatus_paquete,
                p.estatus2 as estatus2_paquete,
                p.metodo_remu,
                u.user_name as solicitante_nombre,
                a.user_name as aprobador_nombre')
            ->join('packages p', 'p.id = r.package_id', 'left')
            ->join('users u', 'u.id = r.solicitado_por', 'left')
            ->join('users a', 'a.id = r.aprobado_por', 'left')
            ->where('r.id', $id)
            ->get()
            ->getRowArray();

        if (!$solicitud) {
            return null;
        }

        // Buscar el pago asociado al paquete
        $payment = $this->db->table('package_payments')
            ->where('package_id', $solicitud['package_id'])
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if ($payment) {
            $pagoId = $payment['pago_id'];

            // Traer el pago general
            $pago = $this->db->table('pagos pg')
                ->select('pg.*, s.seller AS vendedor_nombre')
                ->join('sellers s', 's.id = pg.seller_id', 'left')
                ->where('pg.id', $pagoId)
                ->get()->getRowArray();

            // Traer todos los paquetes del pago con detalle
            $paquetesPago = $this->db->table('package_payments pp')
                ->select('pp.*, p.cliente, p.estatus as estatus_actual, p.estatus2 as estatus2_actual')
                ->join('packages p', 'p.id = pp.package_id', 'left')
                ->where('pp.pago_id', $pagoId)
                ->get()->getResultArray();

            $solicitud['pago']          = $pago;
            $solicitud['paquetes_pago'] = $paquetesPago;
        } else {
            $solicitud['pago']          = null;
            $solicitud['paquetes_pago'] = [];
        }

        return $solicitud;
    }
}
