<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table      = 'pagos';
    protected $primaryKey = 'id';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'seller_id',
        'total_bruto',
        'total_flete',
        'total_neto',
        'metodo',
        'cuenta_id',
        'anulado',
        'anulado_at',
        'anulado_by',
        'created_by',
        'created_at',
    ];

    // -----------------------------------------------
    // Obtener pagos de un vendedor (no anulados)
    // -----------------------------------------------
    public function getPagosBySeller(int $sellerId)
    {
        return $this->where('seller_id', $sellerId)
            ->where('anulado', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // -----------------------------------------------
    // Obtener pago con su detalle de paquetes
    // -----------------------------------------------
    public function getPagoConDetalle(int $pagoId)
    {
        $db = db_connect();

        $pago = $this->find($pagoId);

        if (!$pago) {
            return null;
        }

        $detalle = $db->table('package_payments')
            ->where('pago_id', $pagoId)
            ->get()
            ->getResultArray();

        return [
            'pago'    => $pago,
            'detalle' => $detalle,
        ];
    }

    // -----------------------------------------------
    // Anular pago (solo marca, no revierte)
    // -----------------------------------------------
    public function anularPago(int $pagoId, int $userId): bool
    {
        return $this->update($pagoId, [
            'anulado'    => 1,
            'anulado_at' => date('Y-m-d H:i:s'),
            'anulado_by' => $userId,
        ]);
    }
}