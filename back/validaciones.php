<?php
require_once '../config/basededatos.php';
require_once 'validacionesCliente.php';

header('Content-Type: application/json; charset=utf-8');

$datos = ['ok' => false];

if (isset($_POST['action'])) {
    $db  = new Database();
    $con = $db->conectar();

    // Comparacion con == : antes decia "=" (asignacion), por lo que la rama
    // de email siempre se ejecutaba sin importar la accion recibida.
    if ($_POST['action'] === 'existeUsuario') {
        $datos['ok'] = usuarioExiste($_POST['usuario'] ?? '', $con);
    } elseif ($_POST['action'] === 'existeEmail') {
        $datos['ok'] = emailExiste($_POST['email'] ?? '', $con);
    }
}

echo json_encode($datos);
