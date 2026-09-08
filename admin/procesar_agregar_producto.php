<?php
// ...

require 'config/basededatos.php';
$db = new Database();
$con = $db->conectar();

// ...

// Suponiendo que tienes variables POST para los valores del producto
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$descripcion = $_POST['descripcion'];
$activo = $_POST['activo'];

$sql = $con->prepare("INSERT INTO productos (id, nombre, precio, descripcion, activo) VALUES (:id, :nombre, :precio, :descripcion, :activo)");

$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sql->bindParam(':precio', $precio, PDO::PARAM_INT);
$sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
$sql->bindParam(':activo', $activo, PDO::PARAM_STR);

// Ejecutar la consulta
$sql->execute();

// ...

?>
