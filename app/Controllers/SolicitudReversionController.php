<?php

namespace App\Controllers;

use App\Models\SolicitudReversionModel;
use App\Models\PackageModel;
use App\Models\NotificationModel;

class SolicitudReversionController extends BaseController
{
    protected $solicitudModel;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudReversionModel();
    }

    // ─── INDEX ───────────────────────────────────────────────────────────────

    public function index()
    {
        if (!tienePermiso('ver_solicitudes')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Sin permiso');
        }

        $data['solicitudes'] = $this->solicitudModel->getSolicitudes();

        return view('solicitudes/index', $data);
    }

    // ─── STORE — Crear solicitud desde el botón del paquete ──────────────────

    public function store()
    {
        if (!tienePermiso('solicitar_reversion')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Sin permiso para solicitar reversión.'
            ]);
        }

        $input     = $this->request->getJSON(true);
        $packageId = $input['package_id'] ?? null;
        $motivo    = $input['motivo']     ?? null;
        $password  = $input['password']   ?? null;
        $userId    = session()->get('id');

        if (!$packageId || !$motivo || !$password) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Datos incompletos.'
            ]);
        }

        // Verificar contraseña del usuario logueado
        $db   = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

        if (!$user || !password_verify($password, $user['user_password'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Contraseña incorrecta.'
            ]);
        }

        // Verificar que no haya solicitud pendiente para este paquete
        $existente = $this->solicitudModel
            ->where('package_id', $packageId)
            ->where('estatus', 'pendiente')
            ->first();

        if ($existente) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Ya existe una solicitud pendiente para este paquete.'
            ]);
        }

        // Crear solicitud
        $this->solicitudModel->insert([
            'package_id'     => $packageId,
            'solicitado_por' => $userId,
            'estatus'        => 'pendiente',
            'comentario'     => $motivo,
        ]);

        $solicitudId = $this->solicitudModel->getInsertID();

        // Crear notificación
        $db->table('notifications')->insert([
            'titulo'     => 'Solicitud de reversión de pago',
            'mensaje'    => 'Se solicitó la reversión del pago del paquete #' . $packageId,
            'link'       => base_url('solicitudes/' . $solicitudId),
            'tipo'       => 'reversion',
            'permiso'    => 'aprobar_reversion',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        registrar_bitacora(
            'Solicitar reversión',
            'Solicitudes',
            'Solicitó reversión de pago del paquete #' . $packageId . ' — Motivo: ' . $motivo,
            $solicitudId
        );

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'Solicitud creada correctamente.'
        ]);
    }

    // ─── SHOW — Ver detalle de una solicitud ─────────────────────────────────

    public function show($id)
    {
        if (!tienePermiso('ver_solicitudes')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Sin permiso');
        }

        $solicitud = $this->solicitudModel->getSolicitudDetalle($id);

        if (!$solicitud) {
            return redirect()->to(base_url('solicitudes'))->with('error', 'Solicitud no encontrada');
        }

        return view('solicitudes/show', ['solicitud' => $solicitud]);
    }

    // ─── APROBAR ─────────────────────────────────────────────────────────────

    public function aprobar()
    {
        if (!tienePermiso('aprobar_reversion')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Sin permiso para aprobar reversiones.'
            ]);
        }

        $input       = $this->request->getJSON(true);
        $solicitudId = $input['solicitud_id'] ?? null;
        $comentario  = $input['comentario']   ?? null;
        $userId      = session()->get('id');

        if (!$solicitudId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Datos incompletos.'
            ]);
        }

        $solicitud = $this->solicitudModel->find($solicitudId);

        if (!$solicitud || $solicitud['estatus'] !== 'pendiente') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Solicitud no válida o ya procesada.'
            ]);
        }

        $db = \Config\Database::connect();

        // ===============================
        // 1️⃣ BUSCAR EL PAGO COMPLETO
        // ===============================

        $packagePayment = $db->table('package_payments')
            ->where('package_id', $solicitud['package_id'])
            ->where('revertido', 0)
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if (!$packagePayment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontró el registro de pago del paquete.'
            ]);
        }

        $pagoId = $packagePayment['pago_id'];

        // Traer el pago general
        $pago = $db->table('pagos')
            ->where('id', $pagoId)
            ->get()->getRowArray();

        if (!$pago) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Pago no encontrado.'
            ]);
        }

        // Traer TODOS los paquetes del pago con su snapshot
        $todosPaquetes = $db->table('package_payments')
            ->where('pago_id', $pagoId)
            ->where('revertido', 0)
            ->get()->getResultArray();

        $metodo    = $pago['metodo'];
        $totalNeto = (float) $pago['total_neto'];

        $db->transStart();

        // ===============================
        // 2️⃣ ACTUALIZAR SOLICITUD
        // ===============================

        $this->solicitudModel->update($solicitudId, [
            'estatus'      => 'aprobada',
            'aprobado_por' => $userId,
            'comentario'   => $comentario,
        ]);

        // ===============================
        // 3️⃣ REVERTIR CADA PAQUETE SEGÚN SU TIPO
        // ===============================

        foreach ($todosPaquetes as $pp) {

            $tipo                 = $pp['tipo'];
            $flete_pagado_antes   = (float) $pp['flete_pagado_antes'];
            $flete_pendiente_antes = (float) $pp['flete_pendiente_antes'];

            if ($tipo === 'solo_flete') {
                // ✅ Solo restaurar flete — no tocar estatus ni amount_paid
                $db->table('packages')
                    ->where('id', $pp['package_id'])
                    ->update([
                        'flete_pagado'    => $flete_pagado_antes,
                        'flete_pendiente' => $flete_pendiente_antes,
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);
            } elseif ($tipo === 'con_descuento_flete') {
                // ✅ Revertir pago + restaurar flete
                $db->table('packages')
                    ->where('id', $pp['package_id'])
                    ->update([
                        'estatus'               => 'entregado',
                        'estatus2'              => null,
                        'fecha_remu'            => null,
                        'metodo_remu'           => null,
                        'remunerado_con_cuenta' => null,
                        'remu_user_id'          => null,
                        'amount_paid'           => 0,
                        'flete_pagado'          => $flete_pagado_antes,
                        'flete_pendiente'       => $flete_pendiente_antes,
                        'updated_at'            => date('Y-m-d H:i:s'),
                    ]);
            } else {
                // ✅ normal — revertir pago, flete no se toca
                $db->table('packages')
                    ->where('id', $pp['package_id'])
                    ->update([
                        'estatus'               => 'entregado',
                        'estatus2'              => null,
                        'fecha_remu'            => null,
                        'metodo_remu'           => null,
                        'remunerado_con_cuenta' => null,
                        'remu_user_id'          => null,
                        'amount_paid'           => 0,
                        'updated_at'            => date('Y-m-d H:i:s'),
                    ]);
            }

            // 📸 Marcar package_payment como revertido
            $db->table('package_payments')
                ->where('id', $pp['id'])
                ->update([
                    'revertido'    => 1,
                    'revertido_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // ===============================
        // 4️⃣ MARCAR PAGO COMO ANULADO
        // ===============================

        $db->table('pagos')
            ->where('id', $pagoId)
            ->update([
                'anulado'    => 1,
                'anulado_at' => date('Y-m-d H:i:s'),
                'anulado_by' => $userId,
            ]);

        // ===============================
        // 5️⃣ REVERTIR MOVIMIENTO FINANCIERO
        // ===============================

        if ($metodo === 'cuenta') {

            $cuentaId = (int) $pago['cuenta_id'];

            $account = $db->table('accounts')
                ->where('id', $cuentaId)
                ->get()->getRowArray();

            if (!$account) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Cuenta original no encontrada.'
                ]);
            }

            // Devolver el neto a la cuenta
            $db->table('accounts')
                ->where('id', $cuentaId)
                ->set('balance', "balance + {$totalNeto}", false)
                ->update();

            registrarEntrada(
                $cuentaId,
                $totalNeto,
                "Reversión de remuneración — Pago #{$pagoId}",
                "Solicitud de reversión aprobada #{$solicitudId}",
                '-'
            );
        } elseif ($metodo === 'caja') {

            $cashierSession = $db->table('cashier_sessions')
                ->where('status', 'open')
                ->where('user_id', $userId)
                ->get()->getRowArray();

            if (!$cashierSession) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'No hay una sesión de caja abierta para registrar la reversión.'
                ]);
            }

            $cashier = $db->table('cashier')
                ->where('id', $cashierSession['cashier_id'])
                ->get()->getRowArray();

            $newBalance = (float) $cashier['current_balance'] + $totalNeto;

            $db->table('cashier')
                ->where('id', $cashier['id'])
                ->update(['current_balance' => $newBalance]);

            $cashierMovementModel = new \App\Models\CashierMovementModel();
            $cashierMovementModel->insert([
                'cashier_id'         => $cashier['id'],
                'cashier_session_id' => $cashierSession['id'],
                'user_id'            => $userId,
                'branch_id'          => session()->get('branch_id'),
                'type'               => 'in',
                'amount'             => $totalNeto,
                'balance_after'      => $newBalance,
                'concept'            => "Reversión remuneración — Pago #{$pagoId}",
                'reference_type'     => 'Remuneraciones',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al procesar la reversión.'
            ]);
        }

        registrar_bitacora(
            'Aprobar reversión',
            'Solicitudes',
            "Aprobó reversión del pago #{$pagoId} — Método: {$metodo} — Paquetes: " .
                implode(', ', array_column($todosPaquetes, 'package_id')),
            $userId
        );

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'Reversión aprobada correctamente.'
        ]);
    }

    // ─── DENEGAR ─────────────────────────────────────────────────────────────

    public function denegar()
    {
        if (!tienePermiso('denegar_reversion')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Sin permiso para denegar reversiones.'
            ]);
        }

        $input       = $this->request->getJSON(true);
        $solicitudId = $input['solicitud_id'] ?? null;
        $comentario  = $input['comentario']   ?? null;
        $userId      = session()->get('id');

        if (!$solicitudId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Datos incompletos.'
            ]);
        }

        $solicitud = $this->solicitudModel->find($solicitudId);

        if (!$solicitud || $solicitud['estatus'] !== 'pendiente') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Solicitud no válida o ya procesada.'
            ]);
        }

        $this->solicitudModel->update($solicitudId, [
            'estatus'     => 'denegada',
            'aprobado_por' => $userId,
            'comentario'  => $comentario,
        ]);

        registrar_bitacora(
            'Denegar reversión',
            'Solicitudes',
            'Denegó reversión de pago del paquete #' . $solicitud['package_id'],
            $solicitudId
        );

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'Solicitud denegada.'
        ]);
    }
}
