<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PackageModel;

class PaymentController extends BaseController
{
    public function packagesBySeller($sellerId)
    {
        $packageModel = new PackageModel();
        return $this->response->setJSON(
            $packageModel->getPackagesPendingPaymentBySeller((int)$sellerId)
        );
    }

    public function paySeller()
    {
        helper(['form']);
        $session = session();
        $db      = db_connect();
        $data    = $this->request->getJSON(true);

        if (!$data || !isset($data['seller_id']) || !isset($data['packages'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos']);
        }

        if (count($data['packages']) === 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No hay paquetes seleccionados']);
        }

        $sellerId = (int) $data['seller_id'];
        $packages = $data['packages'];

        // ===============================
        // 1️⃣ VALIDAR SESIÓN DE CAJA
        // ===============================

        $cashierSession = $db->table('cashier_sessions')
            ->where('status', 'open')
            ->where('user_id', $session->get('id'))
            ->get()->getRowArray();

        if (!$cashierSession) {
            return $this->response->setJSON(['success' => false, 'message' => 'No hay una sesión de caja abierta']);
        }

        $cashier = $db->table('cashier')
            ->where('id', $cashierSession['cashier_id'])
            ->get()->getRowArray();

        if (!$cashier) {
            return $this->response->setJSON(['success' => false, 'message' => 'Caja no encontrada']);
        }

        // ===============================
        // 2️⃣ CALCULAR TOTALES
        // ===============================

        $totalSalida  = 0;
        $totalEntrada = 0;
        $packagesDB   = [];

        foreach ($packages as $pkg) {
            $rawId       = $pkg['id'];
            $esSoloFlete = strpos($rawId, 'flete-') === 0;

            if ($esSoloFlete) {
                $rawId = str_replace('flete-', '', $rawId);
            }

            $packageId = (int) $rawId;

            $package = $db->table('packages')
                ->where('id', $packageId)
                ->where('vendedor', $sellerId)
                ->get()->getRowArray();

            if (!$package) {
                return $this->response->setJSON(['success' => false, 'message' => 'Paquete inválido: #' . $pkg['id']]);
            }

            $monto     = (float) ($package['monto'] ?? 0);
            $pendiente = (float) ($package['flete_pendiente'] ?? 0);

            if ($esSoloFlete) {
                // Solo suma el flete al cobro, no paga el monto
                $totalEntrada += $pendiente;
            } else {
                $totalSalida  += $monto;
                $totalEntrada += $pendiente;
            }

            $packagesDB[] = [
                'id'                    => $packageId,
                'monto'                 => $esSoloFlete ? 0 : $monto,
                'pendiente'             => $pendiente,
                'estatus'               => $package['estatus'] ?? null,
                'tipo'                  => $esSoloFlete ? 'solo_flete' : ($pendiente > 0 ? 'con_descuento_flete' : 'normal'),
                'flete_pagado_antes'    => (float) ($package['flete_pagado'] ?? 0),
                'flete_pendiente_antes' => $pendiente,
                'es_solo_flete'         => $esSoloFlete,
            ];
        }

        // ===============================
        // 3️⃣ VALIDAR CAJA
        // ===============================

        $totalNeto = $totalSalida - $totalEntrada;

        if ($totalNeto <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'El total de pago no es válido por ser menor o igual a cero']);
        }

        if ($totalNeto > (float) $cashier['current_balance']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Saldo insuficiente en caja']);
        }

        // ===============================
        // 4️⃣ INICIAR TRANSACCIÓN
        // ===============================

        $db->transStart();

        $pagoModel = new \App\Models\PagoModel();
        $pagoId    = $pagoModel->insert([
            'seller_id'   => $sellerId,
            'total_bruto' => $totalSalida,
            'total_flete' => $totalEntrada,
            'total_neto'  => $totalNeto,
            'metodo'      => 'caja',
            'cuenta_id'   => null,
            'anulado'     => 0,
            'created_by'  => $session->get('id'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        foreach ($packagesDB as $package) {

            // 📸 SNAPSHOT
            $db->table('package_payments')->insert([
                'pago_id'               => $pagoId,
                'seller_id'             => $sellerId,
                'package_id'            => $package['id'],
                'tipo'                  => $package['tipo'],
                'monto_pagado'          => $package['monto'],
                'flete_descontado'      => $package['pendiente'],
                'flete_pagado_antes'    => $package['flete_pagado_antes'],
                'flete_pendiente_antes' => $package['flete_pendiente_antes'],
                'flete_pagado'          => $package['flete_pagado_antes'] + $package['pendiente'],
                'metodo'                => 'caja',
                'revertido'             => 0,
                'created_at'            => date('Y-m-d H:i:s'),
            ]);

            // 🔵 MODIFICAR PAQUETE
            $builder = $db->table('packages')->where('id', $package['id']);

            if ($package['es_solo_flete']) {
                // ✅ Solo cobra el flete, no toca estatus ni amount_paid
                $builder
                    ->set('flete_pagado', "COALESCE(flete_pagado,0) + {$package['pendiente']}", false)
                    ->set('flete_pendiente', 0)
                    ->set('metodo_remu', 'caja')
                    ->set('remu_user_id', $session->get('id'));
            } else {
                // ✅ Pago normal
                $builder
                    ->set('amount_paid', $package['monto'])
                    ->set('flete_pagado', "COALESCE(flete_pagado,0) + {$package['pendiente']}", false)
                    ->set('flete_pendiente', 0)
                    ->set('metodo_remu', 'caja')
                    ->set('remunerado_con_cuenta', 1)
                    ->set('remu_user_id', $session->get('id'));

                if ($package['estatus'] === 'entregado') {
                    $builder
                        ->set('estatus', 'finalizado')
                        ->set('estatus2', 'remunerado')
                        ->set('fecha_remu', date('Y-m-d H:i:s'));
                }
            }

            $builder->update();
        }

        // ===============================
        // 5️⃣ SALIDA BRUTA
        // ===============================

        $cashierMovementModel = new \App\Models\CashierMovementModel();
        $currentBalance       = (float) $cashier['current_balance'];
        $balanceAfterOut      = $currentBalance - $totalSalida;

        if ($totalSalida > 0) {
            $cashierMovementModel->insert([
                'cashier_id'         => $cashier['id'],
                'cashier_session_id' => $cashierSession['id'],
                'user_id'            => $session->get('id'),
                'branch_id'          => $session->get('branch_id'),
                'type'               => 'out',
                'amount'             => $totalSalida,
                'balance_after'      => $balanceAfterOut,
                'concept'            => 'Pago bruto vendedor #' . $sellerId,
                'reference_type'     => 'Remuneraciones',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        // ===============================
        // 6️⃣ ENTRADA POR FLETES
        // ===============================

        $balanceAfterIn = $balanceAfterOut + $totalEntrada;

        if ($totalEntrada > 0) {
            $cashierMovementModel->insert([
                'cashier_id'         => $cashier['id'],
                'cashier_session_id' => $cashierSession['id'],
                'user_id'            => $session->get('id'),
                'branch_id'          => $session->get('branch_id'),
                'type'               => 'in',
                'amount'             => $totalEntrada,
                'balance_after'      => $balanceAfterIn,
                'concept'            => 'Cobro fletes vendedor #' . $sellerId,
                'reference_type'     => 'Fletes',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        // ===============================
        // 7️⃣ ACTUALIZAR SALDO CAJA
        // ===============================

        $db->table('cashier')
            ->where('id', $cashier['id'])
            ->update(['current_balance' => $balanceAfterIn]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al procesar el pago']);
        }

        registrar_bitacora(
            'Pago a vendedor ID ' . esc($sellerId),
            'Remuneraciones',
            'Salida: $' . number_format((float) $totalSalida, 2) .
                ' | Entrada por fletes: $' . number_format((float) $totalEntrada, 2) .
                ' | Neto: $' . number_format((float) $totalNeto, 2),
            $session->get('id')
        );

        return $this->response->setJSON([
            'success'     => true,
            'total_paid'  => $totalNeto,
            'new_balance' => $balanceAfterIn,
            'pago_id'     => $pagoId,
        ]);
    }


    public function paySellerbyAccount()
    {
        helper(['form']);
        $session = session();
        $db      = db_connect();
        $data    = $this->request->getJSON(true);

        if (!$data || empty($data['seller_id']) || empty($data['packages']) || empty($data['cuenta_id'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos']);
        }

        $sellerId  = (int) $data['seller_id'];
        $packages  = $data['packages'];
        $accountId = (int) $data['cuenta_id'];

        // ===============================
        // 1️⃣ CALCULAR TOTALES
        // ===============================

        $totalSalida  = 0;
        $totalEntrada = 0;
        $packagesDB   = [];

        foreach ($packages as $pkg) {
            $rawId       = $pkg['id'];
            $esSoloFlete = strpos($rawId, 'flete-') === 0;

            if ($esSoloFlete) {
                $rawId = str_replace('flete-', '', $rawId);
            }

            $packageId = (int) $rawId;

            $package = $db->table('packages')
                ->where('id', $packageId)
                ->where('vendedor', $sellerId)
                ->get()->getRowArray();

            if (!$package) {
                return $this->response->setJSON(['success' => false, 'message' => 'Paquete inválido: #' . $pkg['id']]);
            }

            $monto     = (float) ($package['monto'] ?? 0);
            $pendiente = (float) ($package['flete_pendiente'] ?? 0);

            if ($esSoloFlete) {
                $totalEntrada += $pendiente;
            } else {
                $totalSalida  += $monto;
                $totalEntrada += $pendiente;
            }

            $packagesDB[] = [
                'id'                    => $packageId,
                'monto'                 => $esSoloFlete ? 0 : $monto,
                'pendiente'             => $pendiente,
                'estatus'               => $package['estatus'] ?? null,
                'tipo'                  => $esSoloFlete ? 'solo_flete' : ($pendiente > 0 ? 'con_descuento_flete' : 'normal'),
                'flete_pagado_antes'    => (float) ($package['flete_pagado'] ?? 0),
                'flete_pendiente_antes' => $pendiente,
                'es_solo_flete'         => $esSoloFlete,
            ];
        }

        // ===============================
        // 2️⃣ VALIDAR CUENTA
        // ===============================

        $account = $db->table('accounts')
            ->where('id', $accountId)
            ->get()->getRowArray();

        if (!$account) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cuenta seleccionada no encontrada']);
        }

        $totalNeto = $totalSalida - $totalEntrada;

        // ===============================
        // 3️⃣ INICIAR TRANSACCIÓN
        // ===============================

        $db->transStart();

        $pagoModel = new \App\Models\PagoModel();
        $pagoId    = $pagoModel->insert([
            'seller_id'   => $sellerId,
            'total_bruto' => $totalSalida,
            'total_flete' => $totalEntrada,
            'total_neto'  => $totalNeto,
            'metodo'      => 'cuenta',
            'cuenta_id'   => $accountId,
            'anulado'     => 0,
            'created_by'  => $session->get('id'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        foreach ($packagesDB as $package) {

            // 📸 SNAPSHOT
            $db->table('package_payments')->insert([
                'pago_id'               => $pagoId,
                'seller_id'             => $sellerId,
                'package_id'            => $package['id'],
                'tipo'                  => $package['tipo'],
                'monto_pagado'          => $package['monto'],
                'flete_descontado'      => $package['pendiente'],
                'flete_pagado_antes'    => $package['flete_pagado_antes'],
                'flete_pendiente_antes' => $package['flete_pendiente_antes'],
                'flete_pagado'          => $package['flete_pagado_antes'] + $package['pendiente'],
                'metodo'                => 'cuenta',
                'revertido'             => 0,
                'created_at'            => date('Y-m-d H:i:s'),
            ]);

            // 🔵 MODIFICAR PAQUETE
            $builder = $db->table('packages')->where('id', $package['id']);

            if ($package['es_solo_flete']) {
                // ✅ Solo cobra el flete, no toca estatus ni amount_paid
                $builder
                    ->set('flete_pagado', "COALESCE(flete_pagado,0) + {$package['pendiente']}", false)
                    ->set('flete_pendiente', 0)
                    ->set('metodo_remu', 'cuenta')
                    ->set('remu_user_id', $session->get('id'));
            } else {
                // ✅ Pago normal
                $builder
                    ->set('amount_paid', $package['monto'])
                    ->set('flete_pagado', "COALESCE(flete_pagado,0) + {$package['pendiente']}", false)
                    ->set('flete_pendiente', 0)
                    ->set('metodo_remu', 'cuenta')
                    ->set('remunerado_con_cuenta', $accountId)
                    ->set('remu_user_id', $session->get('id'));

                if ($package['estatus'] === 'entregado') {
                    $builder
                        ->set('estatus', 'finalizado')
                        ->set('estatus2', 'remunerado')
                        ->set('fecha_remu', date('Y-m-d H:i:s'));
                }
            }

            $builder->update();
        }

        // 🔹 ACTUALIZAR BALANCE CUENTA
        $db->table('accounts')
            ->where('id', $accountId)
            ->set('balance', "balance - {$totalSalida} + {$totalEntrada}", false)
            ->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al procesar el pago']);
        }

        // ===============================
        // 4️⃣ MOVIMIENTOS DE CUENTA
        // ===============================

        if ($totalSalida > 0) {
            registrarSalida(
                $accountId,
                $totalSalida,
                "Remuneración vendedor ID {$sellerId}",
                "Pago de paquetes: ID " . implode(', ', array_column($packages, 'id')),
                '-'
            );
        }

        if ($totalEntrada > 0) {
            registrarEntrada(
                $accountId,
                $totalEntrada,
                "Cobro fletes vendedor ID {$sellerId}, paquetes: ID " . implode(', ', array_column($packages, 'id')),
                "Descuento por flete pendiente",
                '-'
            );
        }

        registrar_bitacora(
            'Pago a vendedor ID ' . esc($sellerId),
            'Remuneraciones por cuenta',
            'Salida: $' . number_format((float) $totalSalida, 2) .
                ' | Entrada por fletes: $' . number_format((float) $totalEntrada, 2) .
                ' | Neto: $' . number_format((float) $totalNeto, 2),
            $session->get('id')
        );

        return $this->response->setJSON([
            'success'    => true,
            'total_paid' => $totalNeto,
            'pago_id'    => $pagoId,
        ]);
    }

    public function fletesPendientesBySeller($sellerId)
    {
        $packageModel = new PackageModel();

        $data = $packageModel->getFletesPendientesModal((int) $sellerId);

        return $this->response->setJSON($data);
    }
    public function detallePagoPorPaquete($packageId)
    {
        $db = db_connect();

        $payment = $db->table('package_payments')
            ->where('package_id', (int) $packageId)
            ->where('revertido', 0)
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if (!$payment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontró un pago activo para este paquete.'
            ]);
        }

        $pagoId = $payment['pago_id'];

        $pago = $db->table('pagos')
            ->where('id', $pagoId)
            ->get()->getRowArray();

        if (!$pago) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pago no encontrado.'
            ]);
        }

        // 🆕 Traer todos los paquetes con más detalle
        $paquetes = $db->table('package_payments pp')
            ->select('
            pp.package_id,
            pp.tipo,
            pp.monto_pagado,
            pp.flete_descontado,
            pp.flete_pendiente_antes,
            p.cliente,
            p.monto      AS monto_original,
            p.estatus    AS estatus_actual
        ')
            ->join('packages p', 'p.id = pp.package_id')
            ->where('pp.pago_id', $pagoId)
            ->get()->getResultArray();

        $seller = $db->table('sellers')
            ->select('id, seller AS nombre')
            ->where('id', $pago['seller_id'])
            ->get()->getRowArray();

        return $this->response->setJSON([
            'success'         => true,
            'pago_id'         => $pagoId,
            'package_id_orig' => (int) $packageId, // 🆕 para resaltar el que originó
            'pago'            => [
                'id'          => $pago['id'],
                'metodo'      => $pago['metodo'],
                'total_bruto' => $pago['total_bruto'],
                'total_flete' => $pago['total_flete'],
                'total_neto'  => $pago['total_neto'],
                'created_at'  => $pago['created_at'],
                'vendedor'    => $seller['nombre'] ?? 'N/A',
            ],
            'paquetes'        => $paquetes,
        ]);
    }
}
