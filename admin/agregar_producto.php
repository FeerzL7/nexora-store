<?php

require 'back/adminFunciones.php';

// Verifica si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminFunciones = new AdminFunciones();

    // Obtén los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $activo = $_POST['activo'];

    // Llama a la función para agregar el producto
    if ($adminFunciones->agregarProducto($id, $nombre, $precio, $descripcion, $activo)) {
        // Producto agregado exitosamente, puedes redirigir o mostrar un mensaje de éxito
        header('Location: agregar_producto.php');
        exit();
    } else {
        // Hubo un error al agregar el producto, puedes redirigir o mostrar un mensaje de error
        header('Location: inicio.php');
        exit();
    }
}

?>

<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <div class="container">
            <h3>Agregar Producto</h3>
            <form class="row g-3" action="procesar_agregar_producto.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="id">Id</label>
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
                <div class="col-md-6">
                    <label for="activo">Activo</label>
                    <input type="text" name="activo" id="activo" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>