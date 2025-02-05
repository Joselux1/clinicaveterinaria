<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios por Rol</title>
</head>
<body>
    <h1>Usuarios por Rol</h1>

    <!-- Formulario para filtrar por rol -->
    <form action="" method="POST">
        <label for="rol">Seleccionar Rol:</label>
        <select name="rol_id" id="rol">
            <option value="">Todos</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['PK_ID_ROL'] ?>" <?= (isset($_POST['rol_id']) && $_POST['rol_id'] == $rol['PK_ID_ROL']) ? 'selected' : '' ?>>
                    <?= $rol['ROL'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <h2>Usuarios con el Rol Seleccionado:</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Rol</th>
        </tr>

        <?php  
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['rol_id'])) {
            $rolSeleccionado = $_POST['rol_id'];
            foreach ($clientes as $cliente) {
                if ($cliente['ID_ROL'] == $rolSeleccionado) {
                    ?>
                    <tr>
                        <td><?= $cliente['PK_ID_CLIENTE'] ?></td>
                        <td><?= $cliente['NOMBRE'] ?></td>
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
                    <?php
                }
            }
        } else {
            // Si no se ha seleccionado un rol, muestra todos los usuarios
            foreach ($clientes as $cliente) {
                ?>
                <tr>
                    <td><?= $cliente['PK_ID_CLIENTE'] ?></td>
                    <td><?= $cliente['NOMBRE'] ?></td>
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
                <?php
            }
        }
        ?>
    </table>
</body>
</html>
