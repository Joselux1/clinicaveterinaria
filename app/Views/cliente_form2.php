<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($cliente) ? 'Editar Cliente' : 'Crear Cliente' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center"><?= isset($cliente) ? 'Editar Cliente' : 'Crear Cliente' ?></h1>

    <!-- Mostrar errores de validación -->
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <form action="<?= isset($cliente) ? base_url('clientes/editar/') . $cliente['PK_ID_CLIENTE'] : base_url('clientes/guardar') ?>" method="post">
        <?= csrf_field(); ?>
        <div class="mb-3">
            <label for="NOMBRE" class="form-label">Nombre</label>
            <input type="text" name="NOMBRE" id="NOMBRE" class="form-control" 
                   value="<?= isset($cliente) ? esc($cliente['NOMBRE']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="CORREO_ELECTRONICO" class="form-label">Correo Electrónico</label>
            <input type="email" name="CORREO_ELECTRONICO" id="CORREO_ELECTRONICO" class="form-control" 
                   value="<?= isset($cliente) ? esc($cliente['CORREO_ELECTRONICO']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="CONTRASEÑA" class="form-label">Contraseña</label>
            <input type="password" name="CONTRASEÑA" id="CONTRASEÑA" class="form-control" 
                   value="" <?= isset($cliente) ? '' : 'required' ?>>
        </div>
        <button type="submit" class="btn btn-success"><?= isset($cliente) ? 'Actualizar' : 'Guardar' ?></button>
        <a href="<?= base_url('clientes') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
