<?php
require 'config/config.php';
require 'config/basededatos.php';
require 'back/validacionesCliente.php';

$db  = new Database();
$con = $db->conectar();

$errors  = [];
$destino = isset($_GET['pago']) ? 'pago' : 'inicio';

if (!empty($_POST)) {
    $usuario  = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (esNulo([$usuario, $password])) {
        $errors[] = "Escribe tu usuario y tu contraseña.";
    }

    if (count($errors) === 0) {
        $errors[] = login($usuario, $password, $con, $destino);
    }
}

$titulo = 'Iniciar sesión | NEXORA';
?>
<!DOCTYPE html>
<html lang="es">

<head><?php include 'back/cabeza.php'; ?></head>

<body>
    <?php include 'header.php'; ?>

    <main class="pagina contenedor">
        <div class="formulario">
            <h1 class="pagina__titulo" style="font-size:2rem">Entra a tu cuenta</h1>

            <?php mostrarMensajes($errors); ?>

            <form action="login.php<?php echo $destino === 'pago' ? '?pago' : ''; ?>" method="post" autocomplete="off">
                <div class="campos">
                    <div class="campo">
                        <label for="usuario">Usuario</label>
                        <input type="text" name="usuario" id="usuario" required autofocus>
                    </div>
                    <div class="campo">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <button class="boton boton--bloque" type="submit">Iniciar sesión</button>
                </div>
            </form>

            <p class="enlace-apoyo">
                ¿Todavía no tienes cuenta? <a href="registro.php">Crea una en un minuto</a>.
            </p>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="<?php echo recurso('js/script.js'); ?>"></script>
</body>

</html>
