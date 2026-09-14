<?php
define("KEY_TOKEN", "ABC.wqc-210727*");
define("PRECIO", "$");
define("MONEDA", "MXN");

// session_start() solo si no hay una sesion activa: evita el warning
// "session has already been started" cuando un archivo incluye a otro.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Numero de articulos en el carrito (suma de cantidades, no de renglones).
$num_cart = 0;
if (!empty($_SESSION['carrito']['productos'])) {
    $num_cart = array_sum($_SESSION['carrito']['productos']);
}

if (!function_exists('e')) {
    /**
     * Escapa texto antes de imprimirlo en HTML.
     * Todo dato que venga de la base de datos o del usuario debe pasar por aqui.
     */
    function e($texto)
    {
        return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('recorta')) {
    /**
     * Recorta un texto sin partir caracteres acentuados.
     * Usa mbstring si está disponible y si no cae a un recorte seguro en UTF-8.
     */
    function recorta($texto, $largo)
    {
        $texto = (string) $texto;
        if (function_exists('mb_substr')) {
            return mb_substr($texto, 0, $largo, 'UTF-8');
        }
        $corte = substr($texto, 0, $largo);
        // Descarta un posible carácter multibyte partido a la mitad.
        return preg_replace('/[\x80-\xBF]+$|[\xC0-\xFF]$/', '', $corte);
    }
}

if (!function_exists('recurso')) {
    /**
     * Añade la fecha de modificación del archivo como parámetro de versión.
     * Sin esto el navegador conserva en caché el CSS viejo y la página se ve
     * sin estilos después de actualizar el sitio.
     */
    function recurso($ruta)
    {
        $disco = dirname(__DIR__) . '/' . ltrim($ruta, '/');
        $fecha = @filemtime($disco);
        return $ruta . ($fecha ? '?v=' . $fecha : '');
    }
}
