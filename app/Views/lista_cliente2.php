<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container mt-5">
    <h1 class="text-center">Listado de Clientes</h1>

    <?php if (session()->getFlashdata('success')): ?>
        
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>

    <a href="<?= base_url('clientes/crear') ?>" class="btn btn-primary mb-3">Crear Cliente</a>

    <form method="GET" action="<?= base_url('clientes') ?>" class="mb-3">
        <div class="container">
            <div class="input-group w-auto">
            <tbody>
                <tr>
                    <!-- Filtro por Nombre -->
                    <td><input type="text" name="NOMBRE" class="form-control" placeholder="Nombre" value="<?= isset($nombre) ? esc($nombre) : '' ?>"></td>

                    <!-- Filtro por Correo Electrónico -->
                    <td><input type="text" name="CORREO_ELECTRONICO" class="form-control" placeholder="Correo" value="<?= esc($correo ?? '') ?>"></td>

                    <!-- Filtro por Contraseña -->
                    <td><input type="text" name="CONTRASEÑA" class="form-control" placeholder="Contraseña" value="<?= esc($contraseña ?? '') ?>"></td>

                    <!-- Filtro por Fecha Baja -->
                    <td><input type="text" name="FECHA_BAJA" class="form-control" placeholder="Fecha Baja" value="<?= esc($fecha_baja ?? '') ?>"></td>

                    <!-- Botón de Buscar -->
                    <td><button type="submit" class="btn btn-primary">Buscar</button></td>
                </tr>
            </tbody>
        </div>
    </form>

    <?php if (!empty($clientes) && is_array($clientes)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                   
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                      
                        <td><?= esc($cliente['NOMBRE']) ?></td>
                        <td><?= esc($cliente['CORREO_ELECTRONICO']) ?></td>
                        <td>
                            <a href="<?= base_url('clientes/editar/' . $cliente['PK_ID_CLIENTE']) ?>" class="btn btn-warning">Editar</a>
                            <a href="<?= base_url('clientes/eliminar/') . esc($cliente['PK_ID_CLIENTE']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
       <!-- paginador -->
        <div class="mt-4">
        <?= $pager->only(['NOMBRE', 'CORREO_ELECTRONICO'])->links('default', 'custom_pagination')?>
        </div>
    <?php else: ?>
        <p class="text-center">No hay clientes registrados.</p>
    <?php endif; ?> 
</div>
</body>
</html>
