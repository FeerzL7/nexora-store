<?php 
    require 'config/configuraciones.php';

    session_destroy();

    header("Location: index.php")
?>