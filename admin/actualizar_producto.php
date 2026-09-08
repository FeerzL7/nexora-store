<?php

require 'config/basededatos.php'; // Asegúrate de tener un archivo que maneje la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurarse de que los campos del formulario están definidos
    if (isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        // Evitar inyección de SQL utilizando consultas preparadas
        $sql = $con->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id");
        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':descripcion', $descripcion);
        $sql->bindParam(':precio', $precio);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            echo "Producto actualizado correctamente";
        } else {
            echo "Error al actualizar el producto: " . $sql->errorInfo()[2];
        }

    } else {
        echo "Todos los campos del formulario son requeridos";
    }
} else {
    echo "Acceso no permitido";
}

?>






<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <div class="container">
            <h3>Actualizar Producto</h3>
            <form class="row g-3" action="actualizar_producto.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="id">id</label>
                    <input type="text" name="id" id="id" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="descripcion">Descripcion</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="precio">Precio</label>
                    <input type="text" name="precio" id="precio" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>