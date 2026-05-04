<?= $this->extend('Layouts/mainbody') ?>
<?= $this->section('content') ?>
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da;
        border-radius: .375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: .75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    }
    .badge-estado {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 10px;
        font-weight: 500;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="header-title mb-0">Respaldos automáticos</h4>
            </div>
            <div class="card-body">

                <!-- Filtros -->
                <form onsubmit="return false" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <small class="text-muted">Cliente</small>
                            <select id="clienteSelect" class="form-control"></select>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Fecha recepción</small>
                            <input type="text" id="fechaFiltro" class="form-control" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                </form>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width:60px">#</th>
                                <th>Cliente</th>
                                <th>Sistema / Base de datos</th>
                                <th>Fecha recepción</th>
                                <th class="text-center">Tamaño</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width:60px">Menú</th>
                            </tr>
                        </thead>
                        <tbody id="backupsTbody">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Cargando...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="pagerContainer" class="d-flex justify-content-center mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal detalle -->
<div class="modal fade" id="detalleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-database me-2"></i>Detalle del backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleBody">
                <div class="text-center"><span class="spinner-border"></span></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    // ── Select2 clientes ─────────────────────────────────────────────
    $('#clienteSelect').select2({
        placeholder: 'Todos los clientes',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '<?= base_url('clientes/buscar') ?>',
            dataType: 'json',
            delay: 250,
            processResults: data => ({ results: data }),
            cache: true
        }
    });

    $('#clienteSelect').on('change', cargar);

    // ── Máscara de fecha ─────────────────────────────────────────────
    document.getElementById('fechaFiltro').addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '');
        if (v.length > 8) v = v.substring(0, 8);
        if (v.length >= 5)      this.value = v.substring(0,2) + '/' + v.substring(2,4) + '/' + v.substring(4);
        else if (v.length >= 3) this.value = v.substring(0,2) + '/' + v.substring(2);
        else                    this.value = v;

        if (this.value === '' || this.value.length === 10) cargar();
    });

    // ── Carga principal ──────────────────────────────────────────────
    function cargar(url) {
        let clienteId = $('#clienteSelect').val() || '';
        let fecha     = document.getElementById('fechaFiltro').value;

        if (fecha && fecha.length === 10) {
            const p = fecha.split('/');
            fecha   = `${p[2]}-${p[1]}-${p[0]}`;
        } else {
            fecha = '';
        }

        const base    = typeof url === 'string' ? url : '<?= base_url('backups_automaticos/lista') ?>';
        const params  = new URLSearchParams({ cliente_id: clienteId, fecha });

        $('#backupsTbody').html(
            '<tr><td colspan="7" class="text-center text-muted py-3">' +
            '<span class="spinner-border spinner-border-sm me-2"></span>Cargando...</td></tr>'
        );

        fetch(base + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            $('#backupsTbody').html(data.tbody);
            $('#pagerContainer').html(data.pager);
        });
    }

    // ── Paginador ────────────────────────────────────────────────────
    $(document).on('click', '#pagerContainer a', function (e) {
        e.preventDefault();
        cargar($(this).attr('href'));
    });

    // ── Modal detalle ────────────────────────────────────────────────
    const detalleModal = new bootstrap.Modal(document.getElementById('detalleModal'));

    $(document).on('click', '.btn-detalle-backup', function () {
        const id = $(this).data('id');
        $('#detalleBody').html('<div class="text-center"><span class="spinner-border"></span></div>');
        detalleModal.show();

        fetch('<?= base_url('backups_automaticos/detalle/') ?>' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(b => {
            const peso = b.peso ? formatBytes(b.peso) : '—';
            $('#detalleBody').html(`
                <table class="table table-sm mb-0">
                    <tr><th>ID</th><td>#${b.id}</td></tr>
                    <tr><th>Archivo</th><td><code style="font-size:.8rem">${b.archivo}</td></tr>
                    <tr><th>Sistema</th><td>${b.origen ?? '—'}</td></tr>
                    <tr><th>Base de datos</th><td>${b.db_nombre ?? '—'}</td></tr>
                    <tr><th>Tamaño</th><td>${peso}</td></tr>
                    <tr><th>IP origen</th><td>${b.ip ?? '—'}</td></tr>
                    <tr><th>Fecha</th><td>${b.fecha}</td></tr>
                    <tr><th>Notas</th><td>${b.notas ?? '—'}</td></tr>
                    <tr><th>Hash</th><td><small class="text-muted">${b.hash ?? '—'}</small></td></tr>
                </table>
            `);
        });
    });

    function formatBytes(bytes) {
        if (bytes < 1024)        return bytes + ' B';
        if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }

    // Carga inicial
    cargar();
});
</script>
<?= $this->endSection() ?>
