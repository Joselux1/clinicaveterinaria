<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <title>Iniciar Sesión</title>
</head>
<body>

    <!-- Login -->
    <div class="container border position-absolute top-50 start-50 translate-middle w-25 p-3">
        <form action="<?= base_url('login/process') ?>" method="post">
            <?= csrf_field(); ?>

            <div class="border border-black text-center text-light bg-primary mb-4 py-3">Iniciar Sesión</div>
        
            <div class="mb-2">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="CORREO_ELECTRONICO" name="CORREO_ELECTRONICO" placeholder="Introduce tu correo" required>
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="CONTRASEÑA" name="CONTRASEÑA" placeholder="Introduce tu contraseña" required>
            </div>

            <a class="d-block mb-3 text-primary" href="<?= base_url('register') ?>">¿Necesitas registrarte?</a>
            
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
