<?php
/**
 * Pinta una tarjeta de producto. Se usa en la portada y en cualquier
 * listado futuro, para que la tarjeta se vea igual en todas partes.
 */
function pintarPieza(array $row)
{
    $id      = (int) $row['id'];
    $final   = precioFinal($row['precio'], $row['descuento']);
    $oferta  = $row['descuento'] > 0;
    $enlace  = 'detalles.php?id=' . $id;
    ?>
    <article class="pieza aparece" data-categoria="<?php echo (int) $row['id_categoria']; ?>">
        <a class="pieza__visual" href="<?php echo $enlace; ?>">
            <span class="pieza__plato" aria-hidden="true"></span>
            <span class="pieza__aro" aria-hidden="true"></span>
            <img class="pieza__foto" src="<?php echo e(imagenProducto($id)); ?>"
                 alt="<?php echo e($row['nombre']); ?>" loading="lazy">
            <?php if ($oferta) { ?>
                <span class="pieza__oferta">-<?php echo (int) $row['descuento']; ?>%</span>
            <?php } ?>
        </a>

        <h3 class="pieza__nombre"><a href="<?php echo $enlace; ?>"><?php echo e($row['nombre']); ?></a></h3>

        <p class="pieza__precio">
            <?php if ($oferta) { ?>
                <strong class="rebajado"><?php echo PRECIO . number_format($final, 2, '.', ','); ?></strong>
                <del><?php echo PRECIO . number_format($row['precio'], 2, '.', ','); ?></del>
            <?php } else { ?>
                <strong><?php echo PRECIO . number_format($row['precio'], 2, '.', ','); ?></strong>
            <?php } ?>
        </p>

        <button class="pieza__agregar" type="button"
                onclick="addProducto(<?php echo $id; ?>, '<?php echo e(addslashes($row['nombre'])); ?>')">
            Agregar al carrito
        </button>
    </article>
    <?php
}
