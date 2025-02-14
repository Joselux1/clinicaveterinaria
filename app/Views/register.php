<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <title>Formulario de Registro</title>
</head>
<body>

        <form action="<?= base_url('register/process') ?>" method="post">
            <?= csrf_field(); ?>
            
            <div class="container border position-absolute top-50 start-50 translate-middle w-25 p-3">
                     <?php if (isset($validation)): ?>
                        <div class="alert alert-danger mb-4">
                            <?= $validation->listErrors() ?>
                        </div>
                   <?php endif; ?>
              
                <div class="border border-black text-center text-light bg-primary mb-4 py-3">Registro</div>
           

                <div class="mb-3">
                    <label for="NOMBRE" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="NOMBRE" name="NOMBRE" autocomplete="name" required>
                </div>

                <div class="mb-3">
                    <label for="CORREO_ELECTRONICO" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="CORREO_ELECTRONICO" name="CORREO_ELECTRONICO" autocomplete="email"  required>
                </div>

                <div class="mb-3">
                    <label for="CONTRASEÑA" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="CONTRASEÑA" name="CONTRASEÑA" autocomplete="new-password" required>
                </div>

                <div class="mb-3">
                    <label for="repetir_contraseña" class="form-label">Repetir Contraseña</label>
                    <input type="password" class="form-control" id="repetir_contraseña" name="repetir_contraseña" autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Registrar</button>
            </div>
        </form>


</body>
</html>
