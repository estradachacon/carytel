<?php
// Variables disponibles: $pago, $solicitud, $isAprobada
?>

<div class="row mb-3">
    <div class="col-md-3">
        <small class="text-muted d-block">Vendedor</small>
        <strong><?= esc($pago['vendedor_nombre'] ?? 'N/A') ?></strong>
    </div>
    <div class="col-md-3">
        <small class="text-muted d-block">Método</small>
        <?php if ($pago['metodo'] === 'caja'): ?>
            <span class="badge badge-success">Caja</span>
        <?php else: ?>
            <span class="badge badge-primary">Cuenta</span>
        <?php endif; ?>
    </div>
    <div class="col-md-2">
        <small class="text-muted d-block">Total bruto</small>
        <strong>$<?= number_format($pago['total_bruto'], 2) ?></strong>
    </div>
    <div class="col-md-2">
        <small class="text-muted d-block">Fletes descontados</small>
        <strong class="text-danger">-$<?= number_format($pago['total_flete'], 2) ?></strong>
    </div>
    <div class="col-md-2">
        <small class="text-muted d-block">
            <?= isset($isAprobada) && $isAprobada ? 'Neto revertido' : 'Neto pagado' ?>
        </small>
        <strong class="text-success">$<?= number_format($pago['total_neto'], 2) ?></strong>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="thead-light">
            <tr>
                <th>#Paquete</th>
                <th>Cliente</th>
                <th>Monto pagado</th>
                <th>Tipo</th>
                <th>Flete descontado</th>
                <th>Estatus actual</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitud['paquetes_pago'] as $pp): ?>
                <tr <?= $pp['package_id'] == $solicitud['package_id'] ? 'class="table-warning"' : '' ?>>
                    <td>
                        <a href="<?= base_url('packages/show/' . $pp['package_id']) ?>"
                            class="btn btn-xs btn-outline-primary">
                            <i class="fa-solid fa-box"></i> #<?= $pp['package_id'] ?>
                        </a>
                        <?php if ($pp['package_id'] == $solicitud['package_id']): ?>
                            <span class="badge badge-warning ml-1">Solicitado</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($pp['cliente'] ?? '—') ?></td>
                    <td><strong>$<?= number_format($pp['monto_pagado'], 2) ?></strong></td>
                    <td>
                        <?php if ($pp['tipo'] === 'solo_flete'): ?>
                            <span class="badge badge-info">Solo flete</span>
                        <?php elseif ($pp['tipo'] === 'con_descuento_flete'): ?>
                            <span class="badge badge-warning">Con descuento flete</span>
                        <?php else: ?>
                            <span class="badge badge-light">Normal</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-danger">
                        <?= $pp['flete_descontado'] > 0
                            ? '-$' . number_format($pp['flete_descontado'], 2)
                            : '<span class="text-muted">—</span>' ?>
                    </td>
                    <td><?= statusBadge($pp['estatus_actual']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>