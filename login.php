<?php

require 'config/config.php';
require 'config/basededatos.php';
require 'back/validacionesCliente.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if (!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);


    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }
    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con, $proceso);
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportZone</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="image/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <section>
        <main class="form-login">
            <h3>Iniciar sesión</h3>
            <?php mostrarMensajes($errors); ?>
            <form class="custom-form" action="login.php" method="post" autocomplete="off">
                <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="custom-btn">Ingresar</button>
                </div>
                <hr>
                <div class="form-group">
                    <p class="register-link">¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
                </div>
            </form>
        </main>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>