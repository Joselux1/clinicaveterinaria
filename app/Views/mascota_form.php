<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Mascota</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= $mascota ? 'Editar' : 'Crear' ?> Mascota</h1>

    <?= \Config\Services::validation()->listErrors() ?>

    <form action="<?= base_url($mascota ? 'mascotas/editar/' . $mascota['PK_ID_MASCOTA'] : 'mascotas/guardar') ?>" method="post">
        <div class="form-group">
            <label for="NOMBRE">Nombre:</label>
            <input type="text" name="NOMBRE" class="form-control" value="<?= old('NOMBRE', esc($mascota['NOMBRE'] ?? '')) ?>" required>
        </div>
        <div class="form-group">
            <label for="ESPECIE">Especie:</label>
            <input type="text" name="ESPECIE" class="form-control" value="<?= old('ESPECIE', esc($mascota['ESPECIE'] ?? '')) ?>" required>
        </div>
        <div class="form-group">
            <label for="RAZA">Raza:</label>
            <input type="text" name="RAZA" class="form-control" value="<?= old('RAZA', esc($mascota['RAZA'] ?? '')) ?>" required>
        </div>
        <div class="form-group">
            <label for="EDAD">Edad:</label>
            <input type="number" name="EDAD" class="form-control" value="<?= old('EDAD', esc($mascota['EDAD'] ?? '')) ?>" required>
        </div>
        <div class="form-group">
            <label for="CLIENTE_ID">ID Cliente:</label>
            <input type="number" name="CLIENTE_ID" class="form-control" value="<?= old('CLIENTE_ID', esc($mascota['CLIENTE_ID'] ?? '')) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?= $mascota ? 'Actualizar' : 'Crear' ?> Mascota</button>
    </form>
</div>
</body>
</html>
