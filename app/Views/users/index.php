<?= $this->extend('Layouts/mainbody') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="header-title">Lista de usuarios</h4>
                <a class="btn btn-primary btn-sm ml-auto" href="<?= base_url('users/create') ?>"><i
                        class="fa-solid fa-plus"></i> Crear usuario</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 mb-2">
                        <label>Tipo de usuario</label>
                        <select class="form-control select2 select-filter" data-placeholder="Seleccione una opción"
                            name="status" multiple="true">
                            <option value="Gerente">Gerente</option>
                            <option value="Pagador">Pagador</option>
                            <option value="Digitador">Digitador</option>
                            <option value="Motorista">Motorista</option>
                        </select>
                    </div>
                </div>
                <table class="table table-bordered" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de Usuario</th>
                            <th>Email</th>
                            <th class="col-1">Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['id']) ?></td>
                                    <td><?= esc($user['user_name']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td class="text-center">
                                        <?php
                                        $roleName = esc($user['role_name']);
                                        $style = []; // Array para definir las clases
                                
                                        // Definimos el estilo basado en el rol
                                        switch ($roleName) {
                                            case 'Gerente':
                                                $style['class'] = 'bg-dark text-white'; // Oscuro y potente
                                                break;
                                            case 'Pagador':
                                                $style['class'] = 'bg-success text-white'; // Éxito y confirmación
                                                break;
                                            case 'Digitador':
                                                $style['class'] = 'bg-info text-white'; // Informativo, texto oscuro para contraste
                                                break;
                                            case 'Motorista':
                                                $style['class'] = 'bg-secondary text-white'; // Advertencia/Atención, texto oscuro para contraste
                                                break;
                                            default:
                                                $style['class'] = 'bg-light text-warning border border-secondary'; // Para cualquier cosa que se escape
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $style['class'] ?> rounded-pill px-3 py-2">
                                            <?= $roleName ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-info"
                                            title="Editar"><i class="fa-solid fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger" title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>