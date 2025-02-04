<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($medicamento) ? 'Editar Medicamento' : 'Crear Medicamento' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center"><?= isset($medicamento) ? 'Editar Medicamento' : 'Crear Medicamento' ?></h1>

    <form action="<?= base_url(isset($medicamento) ? 'medicamentos/editar/' . esc($medicamento['PK_ID_MEDICAMENTO']) : 'medicamentos/guardar') ?>" method="post">
        <?= csrf_field(); ?>

        <div class="mb-3">
            <label for="NOMBRE" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="NOMBRE" name="NOMBRE" value="<?= isset($medicamento) ? esc($medicamento['NOMBRE']) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="DESCRIPCION" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="DESCRIPCION" name="DESCRIPCION" value="<?= isset($medicamento) ? esc($medicamento['DESCRIPCION']) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="CITA_ID" class="form-label">Cita ID</label>
            <input type="number" class="form-control" id="CITA_ID" name="CITA_ID" value="<?= isset($medicamento) ? esc($medicamento['CITA_ID']) : '' ?>" required>
        </div>

        <button type="submit" class="btn btn-primary"><?= isset($medicamento) ? 'Actualizar Medicamento' : 'Crear Medicamento' ?></button>
    </form>
</div>
</body>
</html>
