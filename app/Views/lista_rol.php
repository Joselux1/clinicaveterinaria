<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <h1>Lista de Usuarios</h1>

    <!-- Formulario para filtrar por rol -->
    <form action="<?= site_url('rolcontrolador/filtrarPorRol') ?>" method="POST">
        <label for="rol">Seleccionar Rol:</label>
        <select name="rol_id" id="rol">
            <option value="">Todos</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['PK_ID_ROL'] ?>">
                    <?= $rol['ROL'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <h2>Usuarios:</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo Electrónico</th>
            <th>Rol</th>
        </tr>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= $cliente['PK_ID_CLIENTE'] ?></td>
                <td><?= $cliente['NOMBRE'] ?></td>
                <td><?= $cliente['CORREO_ELECTRONICO'] ?></td>
                <td>
                    <?php
                    foreach ($roles as $rol) {
                        if ($rol['PK_ID_ROL'] == $cliente['ID_ROL']) {
                            echo $rol['ROL'];
                        }
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
