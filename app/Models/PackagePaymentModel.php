<?php

namespace App\Models;

use CodeIgniter\Model;

class PackagePaymentModel extends Model
{
    protected $table      = 'package_payments';
    protected $primaryKey = 'id';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'pago_id',
        'seller_id',
        'package_id',
        'tipo',                  // 'normal' | 'con_descuento_flete'
        'monto_pagado',
        'flete_pagado',
        'flete_descontado',
        'flete_pendiente_antes',
        'flete_pagado_antes',
        'metodo',
        'revertido',
        'revertido_at',
        'created_at',
    ];

    // -----------------------------------------------
    // Obtener último pago válido de un paquete
    // -----------------------------------------------
    public function getUltimoPagoValido(int $packageId)
    {
        return $this->where('package_id', $packageId)
            ->where('revertido', 0)
            ->orderBy('id', 'DESC')
            ->first();
    }

    // -----------------------------------------------
    // Obtener detalle de paquetes por pago
    // -----------------------------------------------
    public function getDetalleByPago(int $pagoId)
    {
        return $this->where('pago_id', $pagoId)
            ->findAll();
    }

    // -----------------------------------------------
    // Obtener paquetes con descuento de flete en un pago
    // -----------------------------------------------
    public function getConDescuentoFlete(int $pagoId)
    {
        return $this->where('pago_id', $pagoId)
            ->where('tipo', 'con_descuento_flete')
            ->findAll();
    }
}