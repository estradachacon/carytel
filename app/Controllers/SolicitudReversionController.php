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

        $db      = \Config\Database::connect();
        $package = $db->table('packages')
            ->where('id', $solicitud['package_id'])
            ->get()->getRowArray();

        if (!$package) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Paquete no encontrado.'
            ]);
        }

        $metodo    = $package['metodo_remu'] ?? 'cuenta';
        $monto     = (float) $package['monto'];
        $packageId = $package['id'];

        $db->transStart();

        // 1. Actualizar solicitud
        $this->solicitudModel->update($solicitudId, [
            'estatus'     => 'aprobada',
            'aprobado_por' => $userId,
            'comentario'  => $comentario,
        ]);

        // 2. Revertir estados del paquete
        $db->table('packages')->where('id', $packageId)->update([
            'estatus'               => 'entregado',
            'estatus2'               => null,
            'fecha_remu'             => null,
            'metodo_remu'            => null,
            'remunerado_con_cuenta'  => null,
            'remu_user_id'           => null,
            'updated_at'             => date('Y-m-d H:i:s'),
        ]);

        // 3. Revertir según método de pago original
        if ($metodo === 'cuenta') {

            $cuentaId = !empty($package['remunerado_con_cuenta']) 
                ? (int) $package['remunerado_con_cuenta'] 
                : 1;

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

            // Devolver el dinero a la cuenta
            $db->table('accounts')
                ->where('id', $cuentaId)
                ->set('balance', "balance + {$monto}", false)
                ->update();

            registrarEntrada(
                $cuentaId,
                $monto,
                "Reversión de remuneración — Paquete #{$packageId}",
                "Solicitud de reversión aprobada #{$solicitudId}",
                '-'
            );
        } elseif ($metodo === 'caja') {

            // Buscar sesión de caja abierta del usuario que aprueba
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

            $newBalance = (float)$cashier['current_balance'] + $monto;

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
                'amount'             => $monto,
                'balance_after'      => $newBalance,
                'concept'            => "Reversión remuneración — Paquete #{$packageId}",
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
            "Aprobó reversión de pago del paquete #{$packageId} — Método: {$metodo}",
            $solicitudId
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
