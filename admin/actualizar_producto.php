<?php
require 'back/sesion.php';
require 'config/basededatos.php';
require 'back/adminFunciones.php';

$db  = new Database();
$con = $db->conectar();

$errors      = [];
$actualizado = false;

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim($_POST['nombre'] ?? '');
    $descripcion  = trim($_POST['descripcion'] ?? '');
    $precio       = $_POST['precio'] ?? '';
    $descuento    = (int) ($_POST['descuento'] ?? 0);
    $id_categoria = (int) ($_POST['id_categoria'] ?? 0);
    $activo       = isset($_POST['activo']) ? 1 : 0;

    if (!$id) {
        $errors[] = "Falta el ID del producto.";
    }
    if (esNulo([$nombre, $descripcion, $precio])) {
        $errors[] = "Nombre, descripción y precio son obligatorios.";
    }
    if (!is_numeric($precio) || $precio < 0) {
        $errors[] = "El precio debe ser un número mayor o igual a cero.";
    }
    if ($descuento < 0 || $descuento > 100) {
        $errors[] = "El descuento debe estar entre 0 y 100.";
    }

    if (count($errors) === 0) {
        $actualizado = actualizarProducto($con, $id, $nombre, $descripcion, $precio, $descuento, $id_categoria, $activo);
    }
}

$producto   = $id ? obtenerProducto($id, $con) : false;
$categorias = listarCategorias($con);

if ($id && !$producto) {
    $errors[] = "No existe ningún producto con el ID $id.";
}
?>
<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Actualizar producto</h1>

        <?php mostrarMensajes($errors); ?>
        <?php if ($actualizado) { ?>
            <div class="alert alert-success">Producto actualizado correctamente.</div>
        <?php } ?>

        <?php if (!$producto) { ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form class="row g-3" action="actualizar_producto.php" method="get">
                        <div class="col-md-4">
                            <label for="id" class="form-label">ID del producto</label>
                            <input type="number" min="1" name="id" id="id" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <a href="inicio.php" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form class="row g-3" action="actualizar_producto.php" method="post" autocomplete="off">
                        <input type="hidden" name="id" value="<?php echo (int) $producto['id']; ?>">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                   value="<?php echo e($producto['nombre']); ?>" maxlength="200" required>
                        </div>
                        <div class="col-md-6">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select name="id_categoria" id="id_categoria" class="form-control">
                                <?php foreach ($categorias as $c) { ?>
                                    <option value="<?php echo (int) $c['id']; ?>"
                                        <?php echo ((int) $c['id'] === (int) $producto['id_categoria']) ? 'selected' : ''; ?>>
                                        <?php echo e($c['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?php echo e($producto['descripcion']); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" min="0" name="precio" id="precio" class="form-control"
                                   value="<?php echo e($producto['precio']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="descuento" class="form-label">Descuento (%)</label>
                            <input type="number" min="0" max="100" step="1" name="descuento" id="descuento" class="form-control"
                                   value="<?php echo (int) $producto['descuento']; ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1"
                                    <?php echo $producto['activo'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="activo">Visible en la tienda</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <a href="inicio.php" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</main>
<?php include 'footer.php'; ?>
