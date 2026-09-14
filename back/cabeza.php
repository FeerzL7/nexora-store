<?php
/**
 * Bloque <head> compartido. Centraliza tipografías, iconos y hoja de estilos
 * para que todas las páginas carguen exactamente lo mismo.
 * $titulo y $descripcion se definen en cada página antes de incluirlo.
 */
require_once __DIR__ . '/ayudas.php';

$prefijo = $prefijo ?? '';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e($titulo ?? 'NEXORA'); ?></title>
<meta name="description" content="<?php echo e($descripcion ?? 'Tienda en línea de ropa, accesorios y joyería. NEXORA: donde empieza tu estilo.'); ?>">
<meta name="theme-color" content="#D81B4A">
<link rel="icon" href="<?php echo $prefijo . recurso('image/favicon.png'); ?>">
<link rel="stylesheet" href="<?php echo $prefijo . recurso('css/vendor/tipografias.css'); ?>">
<link rel="stylesheet" href="<?php echo $prefijo . recurso('css/vendor/boxicons/boxicons.min.css'); ?>">
<link rel="stylesheet" href="<?php echo $prefijo . recurso('css/styles.css'); ?>">
