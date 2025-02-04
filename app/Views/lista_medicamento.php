<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Medicamentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container mt-5">
    <h1 class="text-center">Listado de Medicamentos</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>

    <a href="<?= base_url('medicamentos/crear') ?>" class="btn btn-primary mb-3">Crear Medicamento</a>

    <form method="GET" action="<?= base_url('medicamentos') ?>" class="mb-3">
    <div class="container">
        <div class="input-group w-auto">
                <tbody>
                    <tr>
                        <!-- Filtro por Nombre -->
                        <td><input type="text" name="NOMBRE" class="form-control" placeholder="Nombre" value="<?= isset($nombre) ? esc($nombre) : '' ?>"></td>

                        <!-- Filtro por Descripción -->
                        <td><input type="text" name="DESCRIPCION" class="form-control" placeholder="Descripción" value="<?= esc($descripcion ?? '') ?>"></td>

                        <!-- Filtro por Fecha Baja -->
                        <td><input type="text" name="FECHA_BAJA" class="form-control" placeholder="Fecha Baja" value="<?= esc($fecha_baja ?? '') ?>"></td>

                        <!-- Botón de Buscar -->
                        <td><button type="submit" class="btn btn-primary">Buscar</button></td>
                    </tr>
                </tbody>
       </div>     
    </div>
    </form>

    <?php if (!empty($medicamentos) && is_array($medicamentos)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                   
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicamentos as $medicamento): ?>
                    <tr>
                        
                        <td><?= esc($medicamento['NOMBRE']) ?></td>
                        <td><?= esc($medicamento['DESCRIPCION']) ?></td>
                        
                        <td>
                            <a href="<?= base_url('medicamentos/editar/' . $medicamento['PK_ID_MEDICAMENTO']) ?>" class="btn btn-warning">Editar</a>
                            <a href="<?= base_url('medicamentos/eliminar/') . esc($medicamento['PK_ID_MEDICAMENTO']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar este medicamento?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- paginador -->
        <div class="mt-4">
            <?= $pager->only(['NOMBRE'])->links('default', 'custom_pagination')?>
        </div>
    <?php else: ?>
        <p class="text-center">No hay medicamentos registrados.</p>
    <?php endif; ?> 
</div>
</body>
</html>
