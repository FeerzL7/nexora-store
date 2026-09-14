<?php
/**
 * Funciones compartidas del carrito.
 * carrito.php y realizarpago.php usan estas mismas funciones para que
 * los importes que ve el cliente sean siempre identicos en las dos pantallas.
 */

require_once __DIR__ . '/ayudas.php';

/** Precio con el descuento del producto ya aplicado. */
function precioFinal($precio, $descuento)
{
    return round($precio - ($precio * $descuento / 100), 2);
}

/**
 * Devuelve las lineas del carrito validadas contra la base de datos.
 * Cada linea trae: id, nombre, precio, descuento, precio_final, cantidad, subtotal.
 */
function obtenerCarrito($con)
{
    $lista = [];

    if (empty($_SESSION['carrito']['productos'])) {
        return $lista;
    }

    $sql = $con->prepare(
        "SELECT id, nombre, precio, descuento FROM productos WHERE id = ? AND activo = 1 LIMIT 1"
    );

    foreach ($_SESSION['carrito']['productos'] as $id => $cantidad) {
        $sql->execute([$id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        // El producto se dio de baja o ya no existe: se retira del carrito.
        if (!$row) {
            unset($_SESSION['carrito']['productos'][$id]);
            continue;
        }

        $cantidad = max(1, min(10, (int) $cantidad));
        $_SESSION['carrito']['productos'][$id] = $cantidad;

        $row['precio_final'] = precioFinal($row['precio'], $row['descuento']);
        $row['cantidad']     = $cantidad;
        $row['subtotal']     = $row['precio_final'] * $cantidad;

        $lista[] = $row;
    }

    return $lista;
}

/** Suma de todos los subtotales. */
function totalCarrito(array $lista)
{
    $total = 0;
    foreach ($lista as $linea) {
        $total += $linea['subtotal'];
    }
    return $total;
}

/**
 * Ruta de la imagen principal del producto, con respaldo si no existe.
 * Lleva la fecha del archivo como versión: si sustituyes la foto de un
 * producto, el navegador la vuelve a pedir en vez de servir la vieja.
 */
function imagenProducto($id, $prefijo = '')
{
    $ruta = 'image/productos/' . (int) $id . '/principal.webp';
    if (!file_exists($prefijo . $ruta)) {
        $ruta = 'image/no-photo.webp';
    }
    $fecha = @filemtime($prefijo . $ruta);
    return $prefijo . $ruta . ($fecha ? '?v=' . $fecha : '');
}
