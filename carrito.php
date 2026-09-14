<?php
require 'config/config.php';
require 'config/basededatos.php';
require 'back/carritoLista.php';

$db  = new Database();
$con = $db->conectar();

$lista_carrito = obtenerCarrito($con);
$total         = totalCarrito($lista_carrito);
$piezas        = array_sum(array_column($lista_carrito, 'cantidad'));

$titulo = 'Tu carrito | NEXORA';
?>
<!DOCTYPE html>
<html lang="es">

<head><?php include 'back/cabeza.php'; ?></head>

<body>
    <?php include 'header.php'; ?>

    <main class="pagina contenedor">
        <?php if (!$lista_carrito) { ?>
            <h1 class="pagina__titulo">Tu carrito está vacío</h1>
            <div class="sin-nada">
                <p>Todavía no eliges nada. El catálogo tiene ropa, accesorios y joyería listos para llevarse.</p>
                <a class="boton" href="index.php#catalogo">Ver catálogo</a>
            </div>
        <?php } else { ?>
            <h1 class="pagina__titulo">Tu carrito</h1>

            <div class="lineas">
                <?php foreach ($lista_carrito as $producto) {
                    $_id = (int) $producto['id']; ?>
                    <article class="linea" id="linea_<?php echo $_id; ?>">
                        <a class="linea__foto" href="detalles.php?id=<?php echo $_id; ?>">
                            <img src="<?php echo e(imagenProducto($_id)); ?>"
                                 alt="<?php echo e($producto['nombre']); ?>" loading="lazy">
                        </a>

                        <div>
                            <a class="linea__nombre" href="detalles.php?id=<?php echo $_id; ?>">
                                <?php echo e($producto['nombre']); ?>
                            </a>
                            <p class="linea__unitario">
                                <?php echo PRECIO . number_format($producto['precio_final'], 2, '.', ','); ?> por pieza
                            </p>
                        </div>

                        <div>
                            <label class="solo-lectores" for="cantidad_<?php echo $_id; ?>">
                                Cantidad de <?php echo e($producto['nombre']); ?>
                            </label>
                            <input class="cantidad" type="number" min="1" max="10" step="1"
                                   value="<?php echo (int) $producto['cantidad']; ?>"
                                   id="cantidad_<?php echo $_id; ?>"
                                   onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                        </div>

                        <div class="linea__subtotal" id="subtotal_<?php echo $_id; ?>">
                            <?php echo PRECIO . number_format($producto['subtotal'], 2, '.', ','); ?>
                        </div>

                        <button class="linea__quitar bx bx-trash" type="button"
                                aria-label="Quitar <?php echo e($producto['nombre']); ?> del carrito"
                                onclick="mostrarModal(<?php echo $_id; ?>, '<?php echo e(addslashes($producto['nombre'])); ?>')"></button>
                    </article>
                <?php } ?>
            </div>

            <div class="resumen">
                <div>
                    <p class="resumen__etiqueta">
                        Total por <span id="conteo_piezas"><?php echo (int) $piezas; ?></span> piezas
                        (<?php echo MONEDA; ?>)
                    </p>
                    <p class="resumen__total" id="total">
                        <?php echo PRECIO . number_format($total, 2, '.', ','); ?>
                    </p>
                </div>
                <?php if (isset($_SESSION['user_cliente'])) { ?>
                    <a href="realizarpago.php" class="boton boton--claro">Continuar con el pago</a>
                <?php } else { ?>
                    <a href="login.php?pago" class="boton boton--claro">Iniciar sesión para pagar</a>
                <?php } ?>
            </div>
        <?php } ?>
    </main>

    <div class="dialogo" id="eliminaModal" role="dialog" aria-modal="true" aria-labelledby="dialogo_titulo">
        <div class="dialogo__caja">
            <h2 class="dialogo__titulo" id="dialogo_titulo">Quitar del carrito</h2>
            <p class="dialogo__texto" id="dialogo_texto">¿Quitas esta pieza de tu carrito?</p>
            <div class="dialogo__acciones">
                <button class="boton" type="button" onclick="eliminar()">Quitar</button>
                <button class="boton boton--fantasma" type="button" onclick="ocultarModal()">Conservarla</button>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="<?php echo recurso('js/script.js'); ?>"></script>
</body>

</html>
