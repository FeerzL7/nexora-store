<?php
require 'config/config.php';
require 'config/basededatos.php';
require 'back/carritoLista.php';

$db  = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_cliente'])) {
    header('Location: login.php?pago');
    exit;
}

$lista_carrito = obtenerCarrito($con);
$total         = totalCarrito($lista_carrito);

if (!$lista_carrito) {
    header('Location: carrito.php');
    exit;
}

$titulo = 'Resumen de tu compra | NEXORA';
?>
<!DOCTYPE html>
<html lang="es">

<head><?php include 'back/cabeza.php'; ?></head>

<body>
    <?php include 'header.php'; ?>

    <main class="pagina contenedor">
        <h1 class="pagina__titulo">Revisa tu pedido</h1>

        <div class="lineas">
            <?php foreach ($lista_carrito as $producto) { ?>
                <article class="linea">
                    <span class="linea__foto">
                        <img src="<?php echo e(imagenProducto($producto['id'])); ?>"
                             alt="<?php echo e($producto['nombre']); ?>" loading="lazy">
                    </span>
                    <div>
                        <p class="linea__nombre"><?php echo e($producto['nombre']); ?></p>
                        <p class="linea__unitario">
                            <?php echo (int) $producto['cantidad']; ?> ×
                            <?php echo PRECIO . number_format($producto['precio_final'], 2, '.', ','); ?>
                        </p>
                    </div>
                    <div></div>
                    <div class="linea__subtotal">
                        <?php echo PRECIO . number_format($producto['subtotal'], 2, '.', ','); ?>
                    </div>
                    <div></div>
                </article>
            <?php } ?>
        </div>

        <div class="resumen">
            <div>
                <p class="resumen__etiqueta">Total a pagar (<?php echo MONEDA; ?>)</p>
                <p class="resumen__total"><?php echo PRECIO . number_format($total, 2, '.', ','); ?></p>
            </div>
            <a class="boton boton--fantasma" href="carrito.php"
               style="color:var(--papel);border-color:var(--papel)">Volver al carrito</a>
        </div>

        <p class="enlace-apoyo">
            La pasarela de pago todavía no está conectada. Este es el último paso disponible por ahora.
        </p>
    </main>

    <?php include 'footer.php'; ?>
    <script src="<?php echo recurso('js/script.js'); ?>"></script>
</body>

</html>
