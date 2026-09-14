<?php
require 'config/configuraciones.php';
require 'config/basededatos.php';
require 'back/adminFunciones.php';

// Si ya hay sesion de administrador, no tiene caso volver a pedir el login.
if (isset($_SESSION['user_id']) && ($_SESSION['user_type'] ?? '') === 'admin') {
    header('Location: inicio.php');
    exit;
}

$db  = new Database();
$con = $db->conectar();

$errors = [];
if (!empty($_POST)) {
    $usuario  = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (count($errors) === 0) {
        $errors[] = login($usuario, $password, $con);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Iniciar sesión | Panel NEXORA</title>
    <link rel="icon" href="../image/logo.png">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Panel NEXORA</h3>
                                </div>
                                <div class="card-body">
                                    <?php mostrarMensajes($errors); ?>
                                    <form action="index.php" method="post" autocomplete="off">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuario" type="text" name="usuario" placeholder="Usuario" required autofocus />
                                            <label for="usuario">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña" required />
                                            <label for="password">Contraseña</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Ingresar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; NEXORA <?php echo date('Y'); ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
