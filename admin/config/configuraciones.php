<?php
define("CURRENCY", "MXN");
define("KEY_TOKEN", "EFG.zug-654151*");
define("PRECIO", "$");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('e')) {
    function e($texto)
    {
        return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
    }
}
