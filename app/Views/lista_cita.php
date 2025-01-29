<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Citas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container mt-5">
    <h1 class="text-center">Listado de Citas</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>

    <a href="<?= base_url('citas/crear') ?>" class="btn btn-primary mb-3">Crear Cita</a>

    <form method="GET" action="<?= base_url('citas') ?>" class="mb-3">
        <div class="container d-flex">
            <div class="input-group w-auto">
            <input type="text" name="DIAGNOSTICO" class="form-control" placeholder="Diagnóstico" value="<?= isset($diagnostico) ? esc($diagnostico) : '' ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <?php if (!empty($citas) && is_array($citas)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Diagnóstico</th>
                    <th>Tratamiento</th>
                    <th>Veterinario</th>
                    <th>Mascota</th>
                    <th>Fecha Baja</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?= esc($cita['PK_ID_CITA']) ?></td>
                        <td><?= esc($cita['FECHA']) ?></td>
                        <td><?= esc($cita['DIAGNOSTICO']) ?></td>
                        <td><?= esc($cita['TRATAMIENTO']) ?></td>
                        <td><?= esc($cita['VETERINARIO_ID']) ?></td>
                        <td><?= esc($cita['MASCOTA_ID']) ?></td>
                        <td><?= esc($cita['FECHA_BAJA']) ?></td>
                        <td>
                            <a href="<?= base_url('citas/editar/' . $cita['PK_ID_CITA']) ?>" class="btn btn-warning">Editar</a>
                            <a href="<?= base_url('citas/eliminar/' . esc($cita['PK_ID_CITA'])) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar esta cita?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
       <!-- paginador -->
        <div class="mt-4">
            <?= $pager->only(['DIAGNOSTICO'])->links('default', 'custom_pagination')?>
        </div>
    <?php else: ?>
        <p class="text-center">No hay citas registradas.</p>
    <?php endif; ?> 
</div>
</body>
</html>
