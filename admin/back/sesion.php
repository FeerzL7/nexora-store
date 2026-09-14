<?php
/**
 * Guardia de sesion del panel.
 * Cualquier pagina de /admin debe incluir este archivo antes de imprimir nada:
 * sin el, inicio.php y los formularios de productos quedaban accesibles
 * para cualquiera que escribiera la URL directamente.
 */
require_once __DIR__ . '/../config/configuraciones.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}
