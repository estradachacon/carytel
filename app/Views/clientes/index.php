<?= $this->extend('Layouts/mainbody') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">

            <!-- HEADER -->
            <div class="card-header d-flex align-items-center">
                <h4 class="header-title mb-0">Clientes / Frameworks</h4>
                <button class="btn btn-primary btn-sm ms-auto"
                    data-bs-toggle="modal"
                    data-bs-target="#clienteModal">
                    <i class="fa-solid fa-plus"></i> Nuevo Cliente
                </button>
            </div>

            <div class="card-body">

                <!-- BUSCADOR -->
                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Buscar por nombre o identificador...">
                    </div>
                </div>

                <!-- MOBILE -->
                <div class="d-md-none">
                    <?php foreach ($clientes as $c): ?>
                        <div class="card shadow-sm mb-2 cliente-row">
                            <div class="card-body p-2">

                                <div class="mb-1">
                                    <small class="text-muted">Cliente:</small>
                                    <strong><?= esc($c->nombre) ?></strong>
                                </div>
                                <div class="mb-1">
                                    <small class="text-muted">Identificador:</small>
                                    <?= esc($c->identificador) ?>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">API Key:</small><br>
                                    <?php if (!empty($c->api_key)): ?>
                                        <code class="api-key-display text-break" style="font-size:0.7rem"><?= esc($c->api_key) ?></code>
                                        <div class="mt-1 d-flex gap-1">
                                            <button class="btn btn-xs btn-outline-secondary btn-copy-key"
                                                data-key="<?= esc($c->api_key) ?>" title="Copiar">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                            <button class="btn btn-xs btn-outline-warning btn-regen-key"
                                                data-id="<?= $c->id ?>">
                                                <i class="fa fa-rotate"></i> Regenerar
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Sin clave</span>
                                        <button class="btn btn-xs btn-outline-success btn-regen-key ms-2"
                                            data-id="<?= $c->id ?>">
                                            <i class="fa fa-key"></i> Generar
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-2">
                                    <button class="btn btn-sm btn-outline-warning btn-edit-cliente"
                                        data-id="<?= $c->id ?>"
                                        data-nombre="<?= esc($c->nombre) ?>"
                                        data-identificador="<?= esc($c->identificador) ?>">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <?php if ($c->nombre !== 'Clientes varios'): ?>
                                        <a href="#" class="btn btn-sm btn-outline-danger btn-delete-cliente"
                                            data-id="<?= $c->id ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- DESKTOP -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Identificador</th>
                                    <th>API Key</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $c): ?>
                                    <tr class="cliente-row" data-id="<?= $c->id ?>">

                                        <td><?= esc($c->nombre) ?></td>
                                        <td><?= esc($c->identificador) ?></td>

                                        <td>
                                            <?php if (!empty($c->api_key)): ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <code class="api-key-short text-muted" style="font-size:0.75rem">
                                                        <?= esc(substr($c->api_key, 0, 12)) ?>…
                                                    </code>
                                                    <button class="btn btn-xs btn-outline-secondary btn-copy-key"
                                                        data-key="<?= esc($c->api_key) ?>" title="Copiar clave completa">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-outline-warning btn-regen-key"
                                                        data-id="<?= $c->id ?>" title="Regenerar clave">
                                                        <i class="fa fa-rotate"></i>
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <button class="btn btn-xs btn-outline-success btn-regen-key"
                                                    data-id="<?= $c->id ?>">
                                                    <i class="fa fa-key"></i> Generar clave
                                                </button>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-warning btn-edit-cliente"
                                                data-id="<?= $c->id ?>"
                                                data-nombre="<?= esc($c->nombre) ?>"
                                                data-identificador="<?= esc($c->identificador) ?>">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <?php if ($c->nombre !== 'Clientes varios'): ?>
                                                <a href="#" class="btn btn-sm btn-outline-danger btn-delete-cliente"
                                                    data-id="<?= $c->id ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO / EDITAR -->
<div class="modal fade" id="clienteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="clienteForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="cliente_id">
                    <div class="mb-2">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Identificador</label>
                        <input type="text" name="identificador" id="identificador" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VER API KEY COMPLETA -->
<div class="modal fade" id="apiKeyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-key me-2"></i>API Key generada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-2">Copia esta clave y configúrala en tu framework. No se volverá a mostrar si la regeneras.</p>
                <div class="input-group">
                    <input type="text" id="apiKeyFull" class="form-control font-monospace" readonly>
                    <button class="btn btn-outline-secondary" id="btnCopyModal" title="Copiar">
                        <i class="fa fa-copy"></i>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl  = document.getElementById('clienteModal');
    const modal    = new bootstrap.Modal(modalEl);
    const form     = document.getElementById('clienteForm');
    const title    = document.getElementById('modalTitle');
    const inputId  = document.getElementById('cliente_id');
    const nombre   = document.getElementById('nombre');
    const ident    = document.getElementById('identificador');

    const apiKeyModal = new bootstrap.Modal(document.getElementById('apiKeyModal'));
    const apiKeyFull  = document.getElementById('apiKeyFull');

    // ── Nuevo cliente ──────────────────────────────────────────────────
    document.querySelector('[data-bs-target="#clienteModal"]').addEventListener('click', () => {
        title.innerText  = 'Nuevo Cliente';
        form.action      = '/clientes/create';
        form.reset();
        inputId.value    = '';
    });

    // ── Editar ────────────────────────────────────────────────────────
    document.querySelectorAll('.btn-edit-cliente').forEach(btn => {
        btn.addEventListener('click', function () {
            title.innerText    = 'Editar Cliente';
            form.action        = '/clientes/update/' + this.dataset.id;
            inputId.value      = this.dataset.id;
            nombre.value       = this.dataset.nombre;
            ident.value        = this.dataset.identificador;
            modal.show();
        });
    });

    // ── Eliminar ──────────────────────────────────────────────────────
    document.querySelectorAll('.btn-delete-cliente').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            Swal.fire({
                title: '¿Eliminar cliente?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(r => { if (r.isConfirmed) window.location.href = '/clientes/delete/' + id; });
        });
    });

    // ── Generar / Regenerar API Key ───────────────────────────────────
    document.querySelectorAll('.btn-regen-key').forEach(btn => {
        btn.addEventListener('click', function () {
            const id  = this.dataset.id;
            const row = this.closest('tr') || this.closest('.card');

            Swal.fire({
                title: '¿Generar nueva clave?',
                text: 'La clave anterior quedará inválida de inmediato.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar'
            }).then(async r => {
                if (!r.isConfirmed) return;

                const resp = await fetch('/clientes/generar-api-key/' + id, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await resp.json();

                if (!data.ok) {
                    Swal.fire('Error', data.error ?? 'Error al generar clave', 'error');
                    return;
                }

                // Mostrar modal con la clave completa
                apiKeyFull.value = data.api_key;
                apiKeyModal.show();

                // Recargar la fila: recarga la página completa para simplificar
                setTimeout(() => window.location.reload(), 2500);
            });
        });
    });

    // ── Copiar clave (botones en tabla) ───────────────────────────────
    document.querySelectorAll('.btn-copy-key').forEach(btn => {
        btn.addEventListener('click', function () {
            copiarAlPortapapeles(this.dataset.key, this);
        });
    });

    // ── Copiar desde modal ────────────────────────────────────────────
    document.getElementById('btnCopyModal').addEventListener('click', function () {
        copiarAlPortapapeles(apiKeyFull.value, this);
    });

    function copiarAlPortapapeles(texto, boton) {
        navigator.clipboard.writeText(texto).then(() => {
            const original = boton.innerHTML;
            boton.innerHTML = '<i class="fa fa-check text-success"></i>';
            setTimeout(() => boton.innerHTML = original, 1500);
        });
    }

    // ── Buscador inline ───────────────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.cliente-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });

});
</script>
<?= $this->endSection() ?>
