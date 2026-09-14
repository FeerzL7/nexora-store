<?php
require 'config/config.php';
require 'config/basededatos.php';
require 'back/validacionesCliente.php';

$db  = new Database();
$con = $db->conectar();

$errors     = [];
$registrado = false;

if (!empty($_POST)) {
    $nombres    = trim($_POST['nombres'] ?? '');
    $apellidos  = trim($_POST['apellidos'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $usuario    = trim($_POST['usuario'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $repassword = trim($_POST['repassword'] ?? '');

    if (esNulo([$nombres, $apellidos, $email, $telefono, $usuario, $password, $repassword])) {
        $errors[] = "Faltan campos por llenar.";
    }
    if (!esEmail($email)) {
        $errors[] = "Ese correo no tiene un formato válido.";
    }
    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las dos contraseñas no coinciden.";
    }
    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El usuario $usuario ya está ocupado.";
    }
    if (emailExiste($email, $con)) {
        $errors[] = "Ya hay una cuenta con el correo $email.";
    }

    if (count($errors) === 0) {
        $token     = generaToken();
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $id        = registraCliente([$nombres, $apellidos, $email, $telefono], $con);

        if ($id > 0) {
            if (registraUsuario([$usuario, $pass_hash, $token, $id], $con)) {
                $registrado = true;
            } else {
                $errors[] = "No fue posible crear tu cuenta. Inténtalo de nuevo.";
            }
        } else {
            $errors[] = "No fue posible guardar tus datos. Inténtalo de nuevo.";
        }
    }
}

$titulo = 'Crear cuenta | NEXORA';
?>
<!DOCTYPE html>
<html lang="es">

<head><?php include 'back/cabeza.php'; ?></head>

<body>
    <?php include 'header.php'; ?>

    <main class="pagina contenedor">
        <div class="formulario formulario--ancho">
            <h1 class="pagina__titulo" style="font-size:2rem">Crea tu cuenta</h1>

            <?php mostrarMensajes($errors); ?>

            <?php if ($registrado) { ?>
                <div class="aviso aviso--bien">
                    Tu cuenta quedó lista. <a href="login.php">Inicia sesión</a> para seguir comprando.
                </div>
            <?php } else { ?>
                <form action="registro.php" method="post" autocomplete="off">
                    <div class="campos campos--dos">
                        <div class="campo">
                            <label for="nombres">Nombres</label>
                            <input type="text" name="nombres" id="nombres" maxlength="60" required>
                        </div>
                        <div class="campo">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" maxlength="60" required>
                        </div>
                        <div class="campo">
                            <label for="email">Correo</label>
                            <input type="email" name="email" id="email" maxlength="80" required>
                            <span class="campo__aviso" id="validaEmail"></span>
                        </div>
                        <div class="campo">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" name="telefono" id="telefono" maxlength="20" required>
                        </div>
                        <div class="campo">
                            <label for="usuario">Usuario</label>
                            <input type="text" name="usuario" id="usuario" maxlength="30" required>
                            <span class="campo__aviso" id="validaUsuario"></span>
                        </div>
                        <div class="campo"></div>
                        <div class="campo">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="campo">
                            <label for="repassword">Repite la contraseña</label>
                            <input type="password" name="repassword" id="repassword" required>
                        </div>
                        <div class="campo campo--completo">
                            <button class="boton" type="submit">Crear cuenta</button>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <p class="enlace-apoyo">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>.
            </p>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="<?php echo recurso('js/script.js'); ?>"></script>
</body>

</html>
