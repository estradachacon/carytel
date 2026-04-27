<?= $this->extend('Layouts/mainbody') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">

        <div class="card">

            <!-- HEADER -->
            <div class="card-header d-flex align-items-center">
                <h4 class="header-title mb-0">Clientes</h4>

                <button class="btn btn-primary btn-sm ms-auto"
                    data-bs-toggle="modal"
                    data-bs-target="#clienteModal">
                    <i class="fa-solid fa-plus"></i> Nuevo Cliente
                </button>
            </div>

            <div class="card-body">

                <!-- 🔍 BUSCADOR -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Buscar cliente</label>
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Nombre o teléfono">
                    </div>
                </div>

                <!-- 📋 LISTADO -->

                <!-- 📱 MOBILE -->
                <div class="d-md-none">
                    <?php foreach ($clientes as $c): ?>
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">

                                <div class="mb-1">
                                    <small class="text-muted">Cliente:</small>
                                    <?= $c->nombre ?>
                                </div>

                                <div class="mb-1">
                                    <small class="text-muted">Identificador:</small>
                                    <?= $c->identificador ?>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-2">

                                    <button class="btn btn-sm btn-outline-warning btn-edit-cliente"
                                        data-id="<?= $c->id ?>"
                                        data-nombre="<?= esc($c->nombre) ?>"
                                        data-identificador="<?= esc($c->identificador) ?>">
                                        <i class="fa fa-pen"></i>
                                    </button>

                                    <?php if ($c->nombre !== 'Clientes varios'): ?>
                                        <a href="#"
                                            class="btn btn-sm btn-outline-danger btn-delete-cliente"
                                            data-id="<?= $c->id ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 💻 DESKTOP TABLA -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Identificador</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($clientes as $c): ?>
                                    <tr class="cliente-row">

                                        <td><?= $c->nombre ?></td>

                                        <td><?= $c->identificador ?></td>

                                        <td class="text-end">

                                            <button class="btn btn-sm btn-outline-warning btn-edit-cliente"
                                                data-id="<?= $c->id ?>"
                                                data-nombre="<?= esc($c->nombre) ?>"
                                                data-identificador="<?= esc($c->identificador) ?>">
                                                <i class="fa fa-pen"></i>
                                            </button>

                                            <?php if ($c->nombre !== 'Clientes varios'): ?>
                                                <a href="#"
                                                    class="btn btn-sm btn-outline-danger btn-delete-cliente"
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

<!-- 🧾 MODAL -->
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
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modalEl = document.getElementById('clienteModal');
        const modal = new bootstrap.Modal(modalEl);

        const form = document.getElementById('clienteForm');
        const title = document.getElementById('modalTitle');

        const inputId = document.getElementById('cliente_id');
        const nombre = document.getElementById('nombre');
        const identificador = document.getElementById('identificador');

        // NUEVO
        document.querySelector('[data-bs-target="#clienteModal"]').addEventListener('click', () => {
            title.innerText = 'Nuevo Cliente';
            form.action = '/clientes/create';

            form.reset();
            inputId.value = '';
        });

        // EDITAR
        document.querySelectorAll('.btn-edit-cliente').forEach(btn => {
            btn.addEventListener('click', function() {

                title.innerText = 'Editar Cliente';
                form.action = '/clientes/update/' + this.dataset.id;

                inputId.value = this.dataset.id;
                nombre.value = this.dataset.nombre;
                identificador.value = this.dataset.identificador;

                modal.show();
            });
        });

    });
</script>
<script>
    document.querySelectorAll('.btn-delete-cliente').forEach(btn => {
        btn.addEventListener('click', function(e) {
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
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/clientes/delete/' + id;
                }
            });
        });
    });
</script>
<script>
document.getElementById('searchInput').addEventListener('input', function () {
    let term = this.value.toLowerCase();

    // 📱 MOBILE (cards)
    document.querySelectorAll('.d-md-none .card').forEach(card => {
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(term) ? '' : 'none';
    });

    // 💻 DESKTOP (tabla)
    document.querySelectorAll('.cliente-row').forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});
</script>
<?= $this->endSection() ?>