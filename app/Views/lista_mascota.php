<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Mascotas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Listado de Mascotas</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <form method="GET" action="<?= base_url('mascotas') ?>" class="mb-3">
   
        <div class="container">
            <div class="input-group w-auto">
            <tbody>
                <tr>
                         <!-- Filtro por Nombre -->
                        <td><input type="text" name="NOMBRE" class="form-control" placeholder="Nombre" value="<?= isset($nombre) ? esc($nombre) : '' ?>"></td>

                        <!-- Filtro por Especie -->
                        <td><input type="text" name="ESPECIE" class="form-control" placeholder="Especie" value="<?= esc($especie ?? '') ?>"></td>

                        <!-- Filtro por Raza -->
                        <td><input type="text" name="RAZA" class="form-control" placeholder="Raza" value="<?= esc($raza ?? '') ?>"></td>

                        <!-- Filtro por Edad -->
                        <td><input type="text" name="EDAD" class="form-control" placeholder="Edad" value="<?= esc($edad ?? '') ?>"></td>

                        <!-- Filtro por Fecha Baja -->
                        <td><input type="text" name="FECHA_BAJA" class="form-control" placeholder="Fecha Baja" value="<?= esc($fecha_baja ?? '') ?>"></td>

                        <!-- Botón de Buscar -->
                        <td><button type="submit" class="btn btn-primary">Buscar</button></td>
                </tr>
            </tbody>
        </div>
    </form>


    <?php if (!empty($mascotas) && is_array($mascotas)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Edad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mascotas as $mascota): ?>
                    <tr>
                   
                        <td><?= esc($mascota['NOMBRE']) ?></td>
                        <td><?= esc($mascota['ESPECIE']) ?></td>
                        <td><?= esc($mascota['RAZA']) ?></td>
                        <td><?= esc($mascota['EDAD']) ?></td>
                        <td>
                            <a href="<?= base_url('mascotas/editar/' . $mascota['PK_ID_MASCOTA']) ?>" class="btn btn-warning">Editar</a>
                            <a href="<?= base_url('mascotas/eliminar/' . esc($mascota['PK_ID_MASCOTA'])) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar esta mascota?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4">
            <?= $pager->only(['NOMBRE'])->links('default', 'custom_pagination') ?>
        </div>
    <?php else: ?>
        <p class="text-center">No hay mascotas registradas.</p>
    <?php endif; ?>
</div>
</body>
</html>
