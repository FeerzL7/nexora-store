<?php
require 'config/config.php';
require 'config/basededatos.php';
require 'back/carritoLista.php';
require 'back/piezas.php';

$db  = new Database();
$con = $db->conectar();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

$producto = false;
if ($id) {
    $sql = $con->prepare(
        "SELECT p.id, p.nombre, p.descripcion, p.precio, p.descuento, p.id_categoria,
                COALESCE(c.nombre, 'Catálogo') AS categoria
         FROM productos p
         LEFT JOIN categorias c ON c.id = p.id_categoria
         WHERE p.id = ? AND p.activo = 1 LIMIT 1"
    );
    $sql->execute([$id]);
    $producto = $sql->fetch(PDO::FETCH_ASSOC);
}

$relacionados = [];

if (!$producto) {
    http_response_code(404);
    $titulo = 'Producto no encontrado | NEXORA';
} else {
    $titulo      = $producto['nombre'] . ' | NEXORA';
    $descripcion = recorta($producto['descripcion'], 150);
    $final       = precioFinal($producto['precio'], $producto['descuento']);

    // Galería: cualquier otro .webp dentro de la carpeta del producto.
    $imagenes   = [];
    $dir_images = 'image/productos/' . (int) $producto['id'] . '/';
    if (is_dir($dir_images)) {
        foreach (scandir($dir_images) as $archivo) {
            if ($archivo !== 'principal.webp' && strtolower(pathinfo($archivo, PATHINFO_EXTENSION)) === 'webp') {
                $imagenes[] = $dir_images . $archivo;
            }
        }
    }

    $sql = $con->prepare(
        "SELECT id, nombre, precio, descuento, id_categoria
         FROM productos WHERE activo = 1 AND id_categoria = ? AND id <> ?
         ORDER BY RAND() LIMIT 4"
    );
    $sql->execute([$producto['id_categoria'], $producto['id']]);
    $relacionados = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">

<head><?php include 'back/cabeza.php'; ?></head>

<body>
    <?php include 'header.php'; ?>

    <?php if (!$producto) { ?>
        <main class="pagina contenedor">
            <div class="sin-nada">
                <h1 class="pagina__titulo">Esta pieza ya no está</h1>
                <p>El enlace apunta a un producto que se agotó o se dio de baja. El catálogo completo sigue aquí.</p>
                <a class="boton" href="index.php#catalogo">Ver catálogo</a>
            </div>
        </main>
    <?php } else { ?>
        <main class="contenedor">
            <div class="ficha">
                <div class="ficha__galeria">
                    <div class="ficha__principal">
                        <img src="<?php echo e(imagenProducto($producto['id'])); ?>"
                             alt="<?php echo e($producto['nombre']); ?>">
                    </div>
                    <?php if ($imagenes) { ?>
                        <div class="ficha__miniaturas">
                            <?php foreach ($imagenes as $img) { ?>
                                <img src="<?php echo e($img); ?>" alt="<?php echo e($producto['nombre']); ?>" loading="lazy">
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <div>
                    <p class="ficha__migas">
                        <a href="index.php#catalogo">Catálogo</a> /
                        <a href="index.php?categoria=<?php echo (int) $producto['id_categoria']; ?>#catalogo"><?php echo e($producto['categoria']); ?></a>
                    </p>

                    <h1 class="ficha__titulo"><?php echo e($producto['nombre']); ?></h1>

                    <p class="ficha__precio">
                        <?php echo PRECIO . number_format($final, 2, '.', ','); ?>
                        <?php if ($producto['descuento'] > 0) { ?>
                            <del><?php echo PRECIO . number_format($producto['precio'], 2, '.', ','); ?></del>
                            <span class="ficha__etiqueta"><?php echo (int) $producto['descuento']; ?>% menos</span>
                        <?php } ?>
                    </p>

                    <p class="ficha__descripcion"><?php echo nl2br(e($producto['descripcion'])); ?></p>

                    <div class="ficha__acciones">
                        <button class="boton" type="button"
                                onclick="addProducto(<?php echo (int) $producto['id']; ?>, '<?php echo e(addslashes($producto['nombre'])); ?>')">
                            Agregar al carrito
                        </button>
                        <a class="boton boton--fantasma" href="carrito.php">Ir al carrito</a>
                    </div>
                </div>
            </div>

            <?php if ($relacionados) { ?>
                <section class="seccion">
                    <div class="seccion__encabezado">
                        <h2 class="seccion__titulo">Combina con</h2>
                    </div>
                    <div class="rejilla">
                        <?php foreach ($relacionados as $row) { pintarPieza($row); } ?>
                    </div>
                </section>
            <?php } ?>
        </main>
    <?php } ?>

    <?php include 'footer.php'; ?>
    <script src="<?php echo recurso('js/script.js'); ?>"></script>
</body>

</html>
