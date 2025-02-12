<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <? if (isset($validation)): ?>
        <div style="color: red;">
            <?= $validation->listErrors(); ?>
        </div>
    <? endif; ?>
    <form action="<?= base_url('home/create') ?>" method="post">
        <?= csrf_field(); ?> <!-- Protege el formulario contra CSRF -->
        <label for="NOMBRE">Nombre:</label>
        <input type="text" name="NOMBRE" id="NOMBRE" value="<?= set_value('NOMBRE'); ?>">
        <br><br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="EMAIL" id="EMAIL" value="<?= set_value('EMAIL'); ?>">
        <br><br>
        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>
