<?php
define("CURRENCY", "MXN");
define("KEY_TOKEN", "EFG.zug-654151*");
define("PRECIO", "$");

session_start();

$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}
