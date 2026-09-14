<?php
/**
 * Funciones de apoyo para las plantillas.
 *
 * Se declaran aquí, y no solo en config/config.php, porque las plantillas
 * dependen de ellas: si un archivo de configuración se queda sin actualizar,
 * la página moría a media cabecera con "undefined function" y el navegador
 * mostraba una pantalla en blanco. Con esta copia protegida por
 * function_exists, el sitio sigue funcionando pase lo que pase.
 */

if (!function_exists('e')) {
    /** Escapa texto antes de imprimirlo en HTML. */
    function e($texto)
    {
        return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('recurso')) {
    /**
     * Añade la fecha del archivo como parámetro de versión para que el
     * navegador no sirva una copia vieja del CSS, el JS o las imágenes.
     */
    function recurso($ruta)
    {
        $disco = dirname(__DIR__) . '/' . ltrim($ruta, '/');
        $fecha = @filemtime($disco);
        return $ruta . ($fecha ? '?v=' . $fecha : '');
    }
}

if (!function_exists('recorta')) {
    /** Recorta un texto sin partir caracteres acentuados. */
    function recorta($texto, $largo)
    {
        $texto = (string) $texto;
        if (function_exists('mb_substr')) {
            return mb_substr($texto, 0, $largo, 'UTF-8');
        }
        return preg_replace('/[\x80-\xBF]+$|[\xC0-\xFF]$/', '', substr($texto, 0, $largo));
    }
}

if (!defined('PRECIO'))  { define('PRECIO', '$'); }
if (!defined('MONEDA'))  { define('MONEDA', 'MXN'); }
