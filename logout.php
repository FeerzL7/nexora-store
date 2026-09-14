<?php
require 'config/config.php';

// Vaciar el arreglo antes de destruir la sesion: session_destroy() por si sola
// no limpia $_SESSION en la peticion en curso.
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

header('Location: index.php');
exit;
