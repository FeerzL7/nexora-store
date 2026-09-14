<?php
// Actualiza la cantidad de una linea del carrito o la elimina. Responde JSON.
require '../config/config.php';
require '../config/basededatos.php';
require 'carritoLista.php';

header('Content-Type: application/json; charset=utf-8');

$db  = new Database();
$con = $db->conectar();

$datos  = ['ok' => false];
$action = $_POST['action'] ?? '';
$id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($action === 'agregar' && $id) {
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);

    if ($cantidad && isset($_SESSION['carrito']['productos'][$id])) {
        $_SESSION['carrito']['productos'][$id] = $cantidad;
        $datos['ok'] = true;
    }
} elseif ($action === 'eliminar' && $id) {
    if (isset($_SESSION['carrito']['productos'][$id])) {
        unset($_SESSION['carrito']['productos'][$id]);
        $datos['ok'] = true;
    }
}

// El servidor recalcula y devuelve los importes: el navegador ya no los suma solo,
// asi no se puede desincronizar del carrito real.
$lista = obtenerCarrito($con);
$total = totalCarrito($lista);

$datos['sub']    = PRECIO . '0.00';
$datos['total']  = PRECIO . number_format($total, 2, '.', ',');
$datos['numero'] = array_sum($_SESSION['carrito']['productos'] ?? []);

foreach ($lista as $linea) {
    if ((int) $linea['id'] === (int) $id) {
        $datos['sub'] = PRECIO . number_format($linea['subtotal'], 2, '.', ',');
    }
}

echo json_encode($datos);
