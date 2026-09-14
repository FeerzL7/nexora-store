<?php
// Agrega un producto al carrito. Responde JSON.
require '../config/config.php';
require '../config/basededatos.php';
require 'carritoLista.php';

header('Content-Type: application/json; charset=utf-8');

$datos = ['ok' => false, 'numero' => array_sum($_SESSION['carrito']['productos'] ?? [])];

$id       = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
$cantidad = $cantidad ?: 1;

if ($id) {
    $db  = new Database();
    $con = $db->conectar();

    // Solo se agregan productos que existen y estan activos.
    $sql = $con->prepare("SELECT id FROM productos WHERE id = ? AND activo = 1 LIMIT 1");
    $sql->execute([$id]);

    if ($sql->fetch()) {
        $actual = $_SESSION['carrito']['productos'][$id] ?? 0;
        $_SESSION['carrito']['productos'][$id] = min(10, $actual + $cantidad);

        $datos['ok']     = true;
        $datos['numero'] = array_sum($_SESSION['carrito']['productos']);
    }
}

echo json_encode($datos);
