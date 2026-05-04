<?php

namespace App\Controllers\Api;

use CodeIgniter\Controller;
use App\Models\ClienteFrameworkModel;
use App\Models\BackupFrameworkModel;

/**
 * Recibe backups enviados desde frameworks cliente.
 *
 * Soporta dos formatos:
 *  - multipart/form-data  → campo "archivo" (file) + campo "meta" (JSON string)
 *  - application/json     → { "archivo_b64": "...", "nombre_archivo": "...", "meta": {...} }
 *
 * Autenticación: header  X-Api-Key: {api_key}
 *                o campo POST/JSON api_key
 */
class FrameworkBackupController extends Controller
{
    public function recibir()
    {
        // ── 1. Resolver api_key ──────────────────────────────────────────────
        $apiKey = trim($this->request->getHeaderLine('X-Api-Key'));

        if ($apiKey === '') {
            $ct = strtolower($this->request->getHeaderLine('Content-Type'));
            if (str_contains($ct, 'application/json')) {
                $body   = $this->request->getJSON(true);
                $apiKey = trim($body['api_key'] ?? '');
            } else {
                $apiKey = trim($this->request->getPost('api_key') ?? '');
            }
        }

        if ($apiKey === '') {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'API key requerida']);
        }

        // ── 2. Validar cliente ───────────────────────────────────────────────
        $clienteModel = new ClienteFrameworkModel();
        $cliente = $clienteModel->getByApiKey($apiKey);

        if (!$cliente) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'API key inválida']);
        }

        // ── 3. Recibir archivo ───────────────────────────────────────────────
        $ct = strtolower($this->request->getHeaderLine('Content-Type'));

        if (str_contains($ct, 'multipart/form-data')) {
            [$contenido, $nombreArchivo, $error] = $this->_recibirMultipart();
        } else {
            [$contenido, $nombreArchivo, $error] = $this->_recibirJson();
        }

        if ($error) {
            return $this->response->setStatusCode(422)
                ->setJSON(['ok' => false, 'error' => $error]);
        }

        // ── 4. Metadatos ─────────────────────────────────────────────────────
        $ct2 = strtolower($this->request->getHeaderLine('Content-Type'));
        if (str_contains($ct2, 'application/json')) {
            $body  = $this->request->getJSON(true) ?? [];
            $meta  = $body['meta'] ?? [];
        } else {
            $metaRaw = $this->request->getPost('meta');
            $meta    = $metaRaw ? (json_decode($metaRaw, true) ?? []) : [];
        }

        $origen   = $meta['sistema']   ?? null;
        $dbNombre = $meta['db_nombre'] ?? null;
        $notas    = $meta['notas']     ?? null;

        // ── 5. Hash para detectar duplicados ─────────────────────────────────
        $hash = hash('sha256', $contenido);

        $backupModel = new BackupFrameworkModel();

        if ($backupModel->existeHash($hash)) {
            return $this->response->setJSON([
                'ok'      => true,
                'mensaje' => 'Backup ya registrado (duplicado)',
                'estado'  => 'duplicado',
            ]);
        }

        // ── 6. Guardar archivo ───────────────────────────────────────────────
        $dir = WRITEPATH . 'backups/' . $cliente->id . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext       = pathinfo($nombreArchivo, PATHINFO_EXTENSION) ?: 'sql';
        $filename  = $cliente->id . '_' . date('Ymd_His') . '.' . $ext;
        $rutaFull  = $dir . $filename;

        file_put_contents($rutaFull, $contenido);

        // ── 7. Registrar en BD ───────────────────────────────────────────────
        $id = $backupModel->insert([
            'cliente_id' => $cliente->id,
            'archivo'    => 'backups/' . $cliente->id . '/' . $filename,
            'peso'       => strlen($contenido),
            'fecha'      => date('Y-m-d H:i:s'),
            'hash'       => $hash,
            'ip'         => $this->request->getIPAddress(),
            'origen'     => $origen,
            'db_nombre'  => $dbNombre,
            'notas'      => $notas,
            'estado'     => 'ok',
        ]);

        return $this->response->setJSON([
            'ok'      => true,
            'id'      => $id,
            'mensaje' => 'Backup recibido correctamente',
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function _recibirMultipart(): array
    {
        $file = $this->request->getFile('archivo');

        if (!$file || !$file->isValid()) {
            return [null, null, 'No se recibió ningún archivo'];
        }

        $nombreArchivo = $file->getClientName();
        $contenido     = file_get_contents($file->getTempName());

        return [$contenido, $nombreArchivo, null];
    }

    private function _recibirJson(): array
    {
        $body = $this->request->getJSON(true) ?? [];

        $b64    = $body['archivo_b64']    ?? null;
        $nombre = $body['nombre_archivo'] ?? 'backup.sql';

        if (!$b64) {
            return [null, null, 'Campo archivo_b64 requerido'];
        }

        $contenido = base64_decode($b64, true);

        if ($contenido === false) {
            return [null, null, 'archivo_b64 no es base64 válido'];
        }

        return [$contenido, $nombre, null];
    }
}
