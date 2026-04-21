<?= $this->extend('Layouts/mainbody') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex align-items-center">
                <h4 class="header-title mb-0">
                    <i class="fa-solid fa-rotate-left mr-2"></i>
                    Solicitud de Reversión #<?= $solicitud['id'] ?>
                </h4>
                <a href="<?= base_url('solicitudes') ?>" class="btn btn-sm btn-secondary ml-auto">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

            <div class="card-body">

                <!-- ── DATOS GENERALES ── -->
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-secondary border-bottom pb-1 mb-3">Datos de la Solicitud</h5>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th style="width:40%">Paquete</th>
                                    <td>
                                        <a href="<?= base_url('packages/show/' . $solicitud['package_id']) ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-box"></i> #<?= $solicitud['package_id'] ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Solicitado por</th>
                                    <td><?= esc($solicitud['solicitante_nombre']) ?></td>
                                </tr>
                                <tr>
                                    <th>Fecha solicitud</th>
                                    <td><?= date('d/m/Y H:i', strtotime($solicitud['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Motivo</th>
                                    <td><?= esc($solicitud['comentario'] ?? 'Sin motivo') ?></td>
                                </tr>
                                <tr>
                                    <th>Estatus</th>
                                    <td>
                                        <?php
                                        $badgeClass = match ($solicitud['estatus']) {
                                            'aprobada' => 'badge-success',
                                            'denegada' => 'badge-danger',
                                            default    => 'badge-warning'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= ucfirst($solicitud['estatus']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php if (!empty($solicitud['aprobador_nombre'])): ?>
                                    <tr>
                                        <th>Procesado por</th>
                                        <td><?= esc($solicitud['aprobador_nombre']) ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-secondary border-bottom pb-1 mb-3">Datos del Paquete</h5>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th style="width:40%">Monto</th>
                                    <td><strong>$<?= number_format($solicitud['monto'], 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Estatus paquete</th>
                                    <td><?= statusBadge($solicitud['estatus_paquete']) ?></td>
                                </tr>
                                <tr>
                                    <th>Estatus 2</th>
                                    <td><?= !empty($solicitud['estatus2_paquete']) ? statusBadge($solicitud['estatus2_paquete']) : '<span class="badge badge-light">—</span>' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <?php
                    $isAprobada = $solicitud['estatus'] === 'aprobada';
                    $isPendiente = $solicitud['estatus'] === 'pendiente';
                    $pago = $solicitud['pago'] ?? null;
                ?>

                <!-- ── BLOQUE SEGÚN ESTATUS ── -->
                <?php if ($isPendiente): ?>

                    <?php if ($pago): ?>

                        <h5 class="text-secondary border-bottom pb-1 mb-3">
                            <i class="fa-solid fa-money-bill-transfer mr-1"></i>
                            Detalle del Pago — Se revertirán todos los paquetes listados
                        </h5>

                        <div class="alert alert-warning mb-3" style="font-size:0.875rem;">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                            <strong>Atención:</strong> Este paquete forma parte de un pago grupal.
                            Aprobar la reversión afectará <strong>todos los paquetes listados abajo</strong>.
                        </div>

                        <?php include('_tabla_pago.php'); ?>

                    <?php endif; ?>

                    <div class="d-flex justify-content-end mt-3" style="gap:10px;">
                        <?php if (tienePermiso('denegar_reversion')): ?>
                            <button id="btnDenegar" class="btn btn-danger" data-id="<?= $solicitud['id'] ?>">
                                <i class="fa-solid fa-xmark"></i> Denegar
                            </button>
                        <?php endif; ?>
                        <?php if (tienePermiso('aprobar_reversion')): ?>
                            <button id="btnAprobar" class="btn btn-success" data-id="<?= $solicitud['id'] ?>">
                                <i class="fa-solid fa-check"></i> Aprobar
                            </button>
                        <?php endif; ?>
                    </div>

                <?php else: ?>

                    <?php
                        $alertClass = $isAprobada ? 'alert-success' : 'alert-danger';
                        $iconClass  = $isAprobada ? 'fa-circle-check' : 'fa-circle-xmark';
                        $titulo     = $isAprobada ? 'Reversión Aprobada' : 'Solicitud Denegada';
                    ?>

                    <div class="alert <?= $alertClass ?> mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-solid <?= $iconClass ?> mr-2" style="font-size:1.2rem;"></i>
                            <strong style="font-size:1rem;"><?= $titulo ?></strong>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="d-block" style="opacity:0.8;">Procesado por</small>
                                <strong><?= esc($solicitud['aprobador_nombre'] ?? 'N/A') ?></strong>
                            </div>
                            <div class="col-md-4">
                                <small class="d-block" style="opacity:0.8;">Fecha de resolución</small>
                                <strong><?= !empty($solicitud['updated_at'])
                                    ? date('d/m/Y H:i', strtotime($solicitud['updated_at']))
                                    : '—' ?></strong>
                            </div>
                            <div class="col-md-4">
                                <small class="d-block" style="opacity:0.8;">
                                    <?= $isAprobada ? 'Comentario del aprobador' : 'Motivo del rechazo' ?>
                                </small>
                                <strong><?= esc($solicitud['comentario'] ?? 'Sin comentario') ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php if ($pago): ?>

                        <h5 class="text-secondary border-bottom pb-1 mb-3">
                            <i class="fa-solid fa-clock-rotate-left mr-1"></i>
                            <?= $isAprobada ? 'Paquetes revertidos' : 'Paquetes involucrados en la solicitud' ?>
                        </h5>

                        <?php include('_tabla_pago.php'); ?>

                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
    const btnAprobar = document.getElementById('btnAprobar')
    if (btnAprobar) {
        btnAprobar.addEventListener('click', function () {
            const id = this.dataset.id
            Swal.fire({
                title: 'Aprobar reversión',
                html: `
                    <div style="text-align:left">
                        <div style="margin-bottom:12px;">
                            <label style="font-weight:600; font-size:0.9rem; color:#555;">Comentario (opcional)</label>
                            <textarea id="comentarioAprobar" class="swal2-textarea"
                                placeholder="Agrega un comentario..."
                                style="width:80%; margin-top:4px;"></textarea>
                        </div>
                        <div style="color:#dc3545; font-size:0.85rem;">
                            ⚠️ Esta acción revertirá el pago y actualizará los estados del paquete.
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, aprobar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                preConfirm: () => ({
                    comentario: document.getElementById('comentarioAprobar').value.trim()
                })
            }).then(result => {
                if (!result.isConfirmed) return
                fetch('<?= base_url('solicitudes/aprobar') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ solicitud_id: id, comentario: result.value.comentario })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status !== 'ok') { Swal.fire('Error', data.message, 'error'); return }
                    Swal.fire('Aprobada', 'La reversión fue procesada correctamente.', 'success')
                        .then(() => location.reload())
                })
            })
        })
    }

    const btnDenegar = document.getElementById('btnDenegar')
    if (btnDenegar) {
        btnDenegar.addEventListener('click', function () {
            const id = this.dataset.id
            Swal.fire({
                title: 'Denegar reversión',
                html: `
                    <div style="text-align:left">
                        <div style="margin-bottom:12px;">
                            <label style="font-weight:600; font-size:0.9rem; color:#555;">Motivo del rechazo <span style="color:red">*</span></label>
                            <textarea id="comentarioDenegar" class="swal2-textarea"
                                placeholder="Explica el motivo del rechazo..."
                                style="width:80%; margin-top:4px;"></textarea>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, denegar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                preConfirm: () => {
                    const comentario = document.getElementById('comentarioDenegar').value.trim()
                    if (!comentario) { Swal.showValidationMessage('El motivo es obligatorio para denegar'); return false }
                    return { comentario }
                }
            }).then(result => {
                if (!result.isConfirmed) return
                fetch('<?= base_url('solicitudes/denegar') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ solicitud_id: id, comentario: result.value.comentario })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status !== 'ok') { Swal.fire('Error', data.message, 'error'); return }
                    Swal.fire('Denegada', 'La solicitud fue rechazada.', 'success')
                        .then(() => location.reload())
                })
            })
        })
    }
</script>

<?= $this->endSection() ?>