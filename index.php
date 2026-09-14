<?php
require 'config/config.php';
require 'config/basededatos.php';
require 'back/carritoLista.php';
require 'back/piezas.php';

$db  = new Database();
$con = $db->conectar();

$sql = $con->prepare(
    "SELECT id, nombre, precio, descuento, id_categoria
     FROM productos WHERE activo = 1 ORDER BY id"
);
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = $con->prepare(
    "SELECT c.id, c.nombre, COUNT(p.id) AS piezas
     FROM categorias c
     LEFT JOIN productos p ON p.id_categoria = c.id AND p.activo = 1
     WHERE c.activo = 1
     GROUP BY c.id, c.nombre ORDER BY c.id"
);
$sql->execute();
$categorias = $sql->fetchAll(PDO::FETCH_ASSOC);

// Pieza de la portada y pieza destacada: la de mayor descuento.
$destacado = null;
foreach ($productos as $p) {
    if ($destacado === null || $p['descuento'] > $destacado['descuento']) {
        $destacado = $p;
    }
}

// Una imagen representativa por categoría, para los discos.
$muestra = [];
foreach ($productos as $p) {
    $muestra[$p['id_categoria']] = $muestra[$p['id_categoria']] ?? $p['id'];
}

// Permite llegar desde el pie con una categoría ya elegida.
$filtro_inicial = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0;

$titulo      = 'NEXORA | Donde empieza tu estilo';
$descripcion = 'Ropa, accesorios y joyería en un solo lugar. Envíos a todo México.';
?>
<!DOCTYPE html>
<html lang="es">

<head><?php include 'back/cabeza.php'; ?></head>

<body>
    <?php include 'header.php'; ?>

    <!-- portada -->
    <section class="portada">
        <div>
            <p class="portada__guia">Nueva temporada</p>
            <h1 class="portada__titulo">
                <span class="portada__linea"><span>Tu estilo</span></span>
                <span class="portada__linea"><span>empieza aquí</span></span>
            </h1>
            <p class="portada__texto">
                Ropa, accesorios y joyería elegidos pieza por pieza. Sin catálogos
                interminables: solo lo que de verdad te vas a poner.
            </p>
            <div class="portada__acciones">
                <a class="boton" href="#catalogo">Ver catálogo</a>
                <a class="boton boton--fantasma" href="#categorias">Explorar categorías</a>
            </div>
        </div>

        <div class="portada__marca" aria-hidden="true">
            <span class="portada__aro portada__aro--relleno"></span>
            <span class="portada__aro portada__aro--linea"></span>
            <?php if ($destacado) { ?>
                <img class="portada__pieza" src="<?php echo e(imagenProducto($destacado['id'])); ?>" alt="">
            <?php } ?>
        </div>
    </section>

    <!-- categorías -->
    <section class="seccion contenedor" id="categorias">
        <div class="seccion__encabezado">
            <h2 class="seccion__titulo">Tres formas de armarte</h2>
            <p class="seccion__apoyo">
                Empieza por donde quieras. Cada categoría se filtra sola en el catálogo.
            </p>
        </div>

        <div class="categorias">
            <?php foreach ($categorias as $c) { ?>
                <a class="categoria aparece" href="#catalogo" data-ir-a="<?php echo (int) $c['id']; ?>">
                    <span class="categoria__disco">
                        <?php if (!empty($muestra[$c['id']])) { ?>
                            <img src="<?php echo e(imagenProducto($muestra[$c['id']])); ?>" alt="" loading="lazy">
                        <?php } ?>
                    </span>
                    <span>
                        <span class="categoria__nombre"><?php echo e($c['nombre']); ?></span>
                        <span class="categoria__conteo"><?php echo (int) $c['piezas']; ?> piezas</span>
                    </span>
                </a>
            <?php } ?>
        </div>
    </section>

    <!-- catálogo -->
    <section class="seccion contenedor" id="catalogo">
        <div class="seccion__encabezado">
            <h2 class="seccion__titulo">El catálogo</h2>
            <p class="seccion__apoyo">
                <?php echo count($productos); ?> piezas disponibles ahora mismo.
            </p>
        </div>

        <div class="filtros" role="group" aria-label="Filtrar por categoría">
            <button class="filtro<?php echo $filtro_inicial === 0 ? ' activo' : ''; ?>" data-filtro="0">Todo</button>
            <?php foreach ($categorias as $c) { ?>
                <button class="filtro<?php echo $filtro_inicial === (int) $c['id'] ? ' activo' : ''; ?>"
                        data-filtro="<?php echo (int) $c['id']; ?>"><?php echo e($c['nombre']); ?></button>
            <?php } ?>
        </div>

        <div class="rejilla" id="rejilla">
            <?php foreach ($productos as $row) { pintarPieza($row); } ?>
            <p class="vacio" id="sin_resultados" hidden>No hay piezas en esta categoría todavía.</p>
        </div>
    </section>

    <!-- pieza destacada -->
    <?php if ($destacado && $destacado['descuento'] > 0) {
        $final_dest = precioFinal($destacado['precio'], $destacado['descuento']); ?>
        <section class="destacado">
            <div class="contenedor destacado__caja">
                <div>
                    <p class="destacado__guia">La oferta de la semana</p>
                    <h2 class="destacado__titulo"><?php echo e($destacado['nombre']); ?></h2>
                    <p class="destacado__texto">
                        <?php echo (int) $destacado['descuento']; ?>% menos durante esta semana.
                        Cuando se acaba, se acaba.
                    </p>
                    <p class="destacado__precio">
                        <?php echo PRECIO . number_format($final_dest, 2, '.', ','); ?>
                        <del><?php echo PRECIO . number_format($destacado['precio'], 2, '.', ','); ?></del>
                    </p>
                    <a class="boton boton--claro" href="detalles.php?id=<?php echo (int) $destacado['id']; ?>">
                        Ver la pieza
                    </a>
                </div>
                <div class="destacado__visual" aria-hidden="true">
                    <span class="destacado__disco"></span>
                    <img class="destacado__foto" src="<?php echo e(imagenProducto($destacado['id'])); ?>" alt="">
                </div>
            </div>
        </section>
    <?php } ?>

    <!-- servicios -->
    <div class="contenedor">
        <div class="servicios">
            <div class="servicio">
                <i class="bx bx-package"></i>
                <div>
                    <h3>Envíos a todo México</h3>
                    <p>Gratis en compras desde $999. Llega en 3 a 5 días hábiles.</p>
                </div>
            </div>
            <div class="servicio">
                <i class="bx bx-refresh"></i>
                <div>
                    <h3>Cambios sin costo</h3>
                    <p>Tienes 30 días para cambiar la talla o devolver la pieza.</p>
                </div>
            </div>
            <div class="servicio">
                <i class="bx bx-lock-alt"></i>
                <div>
                    <h3>Pago protegido</h3>
                    <p>Tus datos viajan cifrados. Nosotros nunca vemos tu tarjeta.</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="<?php echo recurso('js/script.js'); ?>"></script>
</body>

</html>
