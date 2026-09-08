<?php
require '../config/config.php';
if (isset($_POST['id'])) {

    $id = $_POST['id'];
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;

    if ($cantidad > 0 && is_numeric($cantidad)) {

        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] += $cantidad;
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }

        $datos['numero'] = count($_SESSION['carrito']['productos']);
        $datos['ok'] = true;
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);
