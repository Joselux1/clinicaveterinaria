<!DOCTYPE html>
<html lang="en">
<head>
    <base href="clinicaveterinaria">
    <title>Clinica Veterinaria Medica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="../assets/media/logos/icons8-favicon-48.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
            <img alt="Logo" src="../assets/media/logos/clinica.png" class="h-300px" />
            <div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                <form id="registerForm" action="<?= base_url('register/process') ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="mb-10 text-center">
                        <h1 class="text-dark mb-3">Crear una Cuenta</h1>
                        <div class="text-gray-400 fw-bold fs-4">
                            ¿Ya tienes una cuenta?
                            <a class="d-block mb-3 text-primary" href="<?= base_url('login') ?>"> Iniciar Sesión aquí</a>
                        </div>
                    </div>
                    <div class="row fv-row mb-7">
                        <div class="col-xl-6">
                            <label for="NOMBRE" class="form-label fw-bolder text-dark fs-6 required">Nombre</label>
                            <input class="form-control form-control-lg form-control-solid" type="text" id="NOMBRE" name="NOMBRE" placeholder="Introduce tu nombre" autocomplete="off" />
                        </div>
                    </div>
                    <div class="fv-row mb-7">
                        <label for="CORREO_ELECTRONICO" class="form-label fw-bolder text-dark fs-6 required">Correo Electrónico</label>
                        <input class="form-control form-control-lg form-control-solid" type="email" id="CORREO_ELECTRONICO" name="CORREO_ELECTRONICO"   placeholder="Introduce tu correo"autocomplete="off" />
                    </div>
                    <div class="mb-10 fv-row">
                        <label for="CONTRASEÑA" class="form-label fw-bolder text-dark fs-6 required">Contraseña</label>
                        <input class="form-control form-control-lg form-control-solid" type="password" id="CONTRASEÑA" name="CONTRASEÑA"   placeholder="Introduce tu contraseña"autocomplete="off" />
                    </div>
                    <div class="fv-row mb-5">
                        <label for="repetir_contraseña" class="form-label fw-bolder text-dark fs-6 required">Repetir Contraseña</label>
                        <input class="form-control form-control-lg form-control-solid" type="password" id="repetir_contraseña" name="repetir_contraseña" placeholder="Repite tu contraseña" autocomplete="off" />
                    </div>
                    <div class="text-center">
                        <button type="submit" id="kt_sign_up_submit" class="btn btn-lg btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let nombre = document.getElementById("NOMBRE").value;
            let correo = document.getElementById("CORREO_ELECTRONICO").value;
            let contraseña = document.getElementById("CONTRASEÑA").value;
            let repetirContraseña = document.getElementById("repetir_contraseña").value;

            if (nombre.length < 3) {
                Swal.fire({
                    title: "Nombre demasiado corto",
                    text: "El nombre debe tener al menos 3 caracteres.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return;
            }

            if (!correo.includes("@")) {
                Swal.fire({
                    title: "Correo inválido",
                    text: "Por favor, ingresa un correo electrónico válido.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            if (contraseña.length < 6) {
                Swal.fire({
                    title: "Contraseña débil",
                    text: "La contraseña debe tener al menos 6 caracteres.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return;
            }

            if (contraseña !== repetirContraseña) {
                Swal.fire({
                    title: "Las contraseñas no coinciden",
                    text: "Asegúrate de escribir la misma contraseña en ambos campos.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            Swal.fire({
                title: "Registro Exitoso!",
                text: "Tu cuenta ha sido creada correctamente.",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                this.submit();
            });
        });
    </script>
</body>
</html>