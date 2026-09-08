<?php

require 'back/adminFunciones.php';
require 'config/basededatos.php';
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el campo 'id' está presente en la solicitud
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Llamar a una función para eliminar el producto por su ID
        $eliminado = eliminarProducto($id);

        if ($eliminado) {
            echo "Producto eliminado correctamente.";
        } else {
            echo "Error al eliminar el producto.";
        }
    } else {
        echo "El campo 'id' es requerido para la eliminación.";
    }
} else {
    echo "Acceso no permitido.";
}

?>

<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
    <div class="container">
            <h3>Eliminar Producto</h3>
            <form class="row g-3" action="" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="id">id</label>
                    <input type="number" min="1" max="10" step="1" name="id" id="id" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Eliminar Producto</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>