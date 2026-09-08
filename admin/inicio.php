<?php

require 'back/adminFunciones.php';
require 'config/configuraciones.php';
require 'config/basededatos.php';
$db = new Database();
$con = $db->conectar();
$sql = $con->prepare("SELECT id, nombre, precio, descripcion FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if (eliminarProducto($id)) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar el producto.";
    }
    // Resto del código para procesar la eliminación del producto
} else {
    echo "El campo 'id' no está definido.";
}


?>
<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">SportZone</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"></li>
        </ol>
    </div>
</main>
<?php include 'footer.php'; ?>