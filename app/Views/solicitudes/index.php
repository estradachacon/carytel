<?= $this->extend('Layouts/mainbody') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex align-items-center">
                <h4 class="header-title mb-0">Solicitudes de Reversión</h4>
            </div>

            <div class="card-body">

                <?php if (!empty($solicitudes)): ?>

                    <div class="list-group list-group-flush shadow-sm rounded border">

                        <?php foreach ($solicitudes as $s): ?>
                            <div class="list-group-item py-3">

                                <!-- HEADER -->
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold text-dark">
                                            Paquete #<?= esc($s['package_id']) ?>
                                        </h6>
                                        <small class="text-muted">
                                            Solicitud #<?= esc($s['id']) ?>
                                        </small>
                                    </div>

                                    <div class="text-right">
                                        <?php
                                        $badgeClass = match($s['estatus']) {
                                            'aprobada'  => 'badge-success',
                                            'denegada'  => 'badge-danger',
                                            default     => 'badge-warning'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= ucfirst(esc($s['estatus'])) ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- INFO -->
                                <div class="mt-2 small text-secondary">
                                    <i class="fa-solid fa-user"></i>
                                    Solicitado por: <strong><?= esc($s['solicitante_nombre']) ?></strong>
                                </div>
                                <div class="mt-1 small text-muted">
                                    <i class="fa-solid fa-clock"></i>
                                    <?= date('d/m/Y H:i', strtotime($s['created_at'])) ?>
                                </div>

                                <?php if (!empty($s['comentario'])): ?>
                                    <div class="mt-2 small text-dark">
                                        <i class="fa-solid fa-comment"></i>
                                        <?= esc($s['comentario']) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- ACTIONS -->
                                <div class="d-flex justify-content-end mt-3" style="gap:8px;">
                                    <a href="<?= base_url('solicitudes/' . $s['id']) ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye"></i> Ver
                                    </a>
                                </div>

                            </div>
                        <?php endforeach; ?>

                    </div>

                <?php else: ?>
                    <div class="alert alert-light text-center border">
                        No hay solicitudes de reversión registradas.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>