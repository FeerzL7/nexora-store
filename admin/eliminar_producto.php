<?php
require 'back/sesion.php';
require 'config/basededatos.php';
require 'back/adminFunciones.php';

$db  = new Database();
$con = $db->conectar();

$errors    = [];
$eliminado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if (!$id) {
        $errors[] = "Indica un ID de producto válido.";
    } elseif (!obtenerProducto($id, $con)) {
        $errors[] = "No existe ningún producto con el ID $id.";
    } else {
        $eliminado = eliminarProducto($con, $id);
        if (!$eliminado) {
            $errors[] = "El producto ya estaba dado de baja.";
        }
    }
}

$productos = listarProductos($con);
?>
<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dar de baja un producto</h1>

        <?php mostrarMensajes($errors); ?>
        <?php if ($eliminado) { ?>
            <div class="alert alert-success">Producto dado de baja. Ya no aparece en la tienda.</div>
        <?php } ?>

        <div class="card mb-4">
            <div class="card-body">
                <p class="text-muted">
                    El producto se marca como inactivo, no se borra de la base de datos.
                    Puedes reactivarlo desde <em>Actualizar</em>.
                </p>
                <form class="row g-3" action="eliminar_producto.php" method="post" autocomplete="off"
                      onsubmit="return confirm('¿Dar de baja este producto?');">
                    <div class="col-md-6">
                        <label for="id" class="form-label">Producto</label>
                        <select name="id" id="id" class="form-control" required>
                            <option value="">Selecciona…</option>
                            <?php foreach ($productos as $p) {
                                if (!$p['activo']) continue; ?>
                                <option value="<?php echo (int) $p['id']; ?>">
                                    <?php echo (int) $p['id'] . ' — ' . e($p['nombre']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger">Dar de baja</button>
                        <a href="inicio.php" class="btn btn-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
