<?php

require 'config/config.php';
require 'config/basededatos.php';
require 'back/validacionesCliente.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La direccion de correo no es valida";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo electronico $email ya existe";
    }

    if (count($errors) == 0) {

        $id = registraCliente([$nombres, $apellidos, $email, $telefono], $con);

        if ($id > 0) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $token = generaToken();
            if (!registraUsuario([$usuario, $pass_hash, $token, $id], $con)) {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
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
        <div class="conteiner">
            <h3>Datos del Cliente</h3>
            <?php mostrarMensajes($errors); ?>
            <form action="registro.php" method="post" autocomplete="off">
                <div>
                    <label for="nombres"><span class="text-danger">*</span>Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" requireda>
                </div>
                <div>
                    <label for="apellidos"><span class="text-danger">*</span>Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" requireda>
                </div>
                <div>
                    <label for="email"><span class="text-danger">*</span>Correo Electronico</label>
                    <input type="email" name="email" id="email" class="form-control" requireda>
                    <span id="validaEmail" class="text-danger">Correo no disponible</span>
                </div>
                <div>
                    <label for="telefono"><span class="text-danger">*</span>Telefono</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
                </div>
                <div>
                    <label for="usuario"><span class="text-danger">*</span>Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                    <span id="validaUsuario" class="text-danger">Usuario no disponible</span>
                </div>
                <div>
                    <label for="password"><span class="text-danger">*</span>Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" requireda>
                </div>
                <div>
                    <label for="repassword"><span class="text-danger">*</span>Repetir Contraseña</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" requireda>
                </div>
                <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>