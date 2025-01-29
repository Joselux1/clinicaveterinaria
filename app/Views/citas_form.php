<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($cita) ? 'Editar Cita' : 'Crear Cita' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center"><?= isset($cita) ? 'Editar Cita' : 'Crear Cita' ?></h1>

    <!-- Mostrar errores de validación -->
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <form action="<?= isset($cita) ? base_url('citas/editar/') . $cita['PK_ID_CITA'] : base_url('citas/guardar') ?>" method="post">
        <?= csrf_field(); ?>
        
        <!-- Fecha de la cita -->
        <div class="mb-3">
            <label for="FECHA" class="form-label">Fecha</label>
            <input type="date" name="FECHA" id="FECHA" class="form-control" 
                   value="<?= isset($cita) ? esc($cita['FECHA']) : '' ?>" required>
        </div>

        <!-- Diagnóstico -->
        <div class="mb-3">
            <label for="DIAGNOSTICO" class="form-label">Diagnóstico</label>
            <textarea name="DIAGNOSTICO" id="DIAGNOSTICO" class="form-control" required><?= isset($cita) ? esc($cita['DIAGNOSTICO']) : '' ?></textarea>
        </div>

        <!-- Tratamiento -->
        <div class="mb-3">
            <label for="TRATAMIENTO" class="form-label">Tratamiento</label>
            <textarea name="TRATAMIENTO" id="TRATAMIENTO" class="form-control" required><?= isset($cita) ? esc($cita['TRATAMIENTO']) : '' ?></textarea>
        </div>
        
<!-- Veterinario -->
        <div class="mb-3">
            <label for="VETERINARIO_ID" class="form-label">Veterinario</label>
            <select name="VETERINARIO_ID" id="VETERINARIO_ID" class="form-control" required>
                <option value="">Seleccione un veterinario</option>
                <?php foreach ($veterinarios as $veterinario): ?>
                    <option value="<?= esc($veterinario['PK_ID_VETERINARIO']) ?>" 
                            <?= isset($cita) && $cita['VETERINARIO_ID'] == $veterinario['PK_ID_VETERINARIO'] ? 'selected' : '' ?>>
                        <?= esc($veterinario['VETERINARIO']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Mascota -->
        <div class="mb-3">
            <label for="MASCOTA_ID" class="form-label">Mascota</label>
            <select name="MASCOTA_ID" id="MASCOTA_ID" class="form-control" required>
                <option value="">Seleccione una mascota</option>
                <?php foreach ($mascotas as $mascota): ?>
                    <option value="<?= esc($mascota['PK_ID_MASCOTA']) ?>" 
                            <?= isset($cita) && $cita['MASCOTA_ID'] == $mascota['PK_ID_MASCOTA'] ? 'selected' : '' ?>>
                        <?= esc($mascota['MASCOTA']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Fecha de baja -->
        <div class="mb-3">
            <label for="FECHA_BAJA" class="form-label">Fecha de Baja</label>
            <input type="date" name="FECHA_BAJA" id="FECHA_BAJA" class="form-control" 
                   value="<?= isset($cita) ? esc($cita['FECHA_BAJA']) : '' ?>">
        </div>

        <!-- Botones -->
        <button type="submit" class="btn btn-success"><?= isset($cita) ? 'Actualizar' : 'Guardar' ?></button>
        <a href="<?= base_url('citas') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
