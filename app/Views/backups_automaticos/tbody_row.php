<?php if (!empty($backups)): ?>
    <?php foreach ($backups as $b): ?>
        <tr>
            <td class="text-center text-muted"><?= $b->id ?></td>

            <td><?= esc($b->cliente_nombre ?? '—') ?></td>

            <td>
                <?php if ($b->origen): ?>
                    <span class="fw-semibold"><?= esc($b->origen) ?></span><br>
                <?php endif; ?>
                <?php if ($b->db_nombre): ?>
                    <small class="text-muted"><i class="fa fa-database me-1"></i><?= esc($b->db_nombre) ?></small>
                <?php endif; ?>
                <?php if (!$b->origen && !$b->db_nombre): ?>
                    <span class="text-muted fst-italic">Sin datos</span>
                <?php endif; ?>
            </td>

            <td>
                <?= date('d/m/Y', strtotime($b->fecha)) ?>
                <br>
                <small class="text-muted"><?= date('H:i:s', strtotime($b->fecha)) ?></small>
            </td>

            <td class="text-center">
                <?php
                $bytes = (int) ($b->peso ?? 0);
                if ($bytes === 0)          echo '<span class="text-muted">—</span>';
                elseif ($bytes < 1024)     echo $bytes . ' B';
                elseif ($bytes < 1048576)  echo number_format($bytes / 1024, 1) . ' KB';
                else                       echo number_format($bytes / 1048576, 2) . ' MB';
                ?>
            </td>

            <td class="text-center">
                <?php
                $estado = $b->estado ?? 'ok';
                $map = [
                    'ok'        => ['bg-success', 'Recibido'],
                    'duplicado' => ['bg-secondary', 'Duplicado'],
                    'error'     => ['bg-danger', 'Error'],
                ];
                [$cls, $label] = $map[$estado] ?? ['bg-secondary', esc($estado)];
                echo "<span class=\"badge badge-estado {$cls} text-white\">{$label}</span>";
                ?>
            </td>

            <td class="text-center">
                <button class="btn btn-sm btn-info btn-detalle-backup"
                    data-id="<?= $b->id ?>" title="Ver detalle">
                    <i class="fa fa-eye"></i>
                </button>
                <a href="/backups_automaticos/descargar/<?= $b->id ?>"
                    class="btn btn-sm btn-outline-success ms-1" title="Descargar">
                    <i class="fa fa-download"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="fa fa-inbox fa-2x d-block mb-2 opacity-25"></i>
            No hay respaldos registrados
        </td>
    </tr>
<?php endif; ?>
