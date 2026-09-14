<?php
require 'back/sesion.php';
require 'config/basededatos.php';
require 'back/adminFunciones.php';

$db  = new Database();
$con = $db->conectar();

$errors = [];
$nuevo_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim($_POST['nombre'] ?? '');
    $descripcion  = trim($_POST['descripcion'] ?? '');
    $precio       = $_POST['precio'] ?? '';
    $descuento    = (int) ($_POST['descuento'] ?? 0);
    $id_categoria = (int) ($_POST['id_categoria'] ?? 0);
    $activo       = isset($_POST['activo']) ? 1 : 0;

    if (esNulo([$nombre, $descripcion, $precio])) {
        $errors[] = "Nombre, descripción y precio son obligatorios.";
    }
    if (!is_numeric($precio) || $precio < 0) {
        $errors[] = "El precio debe ser un número mayor o igual a cero.";
    }
    if ($descuento < 0 || $descuento > 100) {
        $errors[] = "El descuento debe estar entre 0 y 100.";
    }
    if ($id_categoria <= 0) {
        $errors[] = "Selecciona una categoría.";
    }

    if (count($errors) === 0) {
        $nuevo_id = agregarProducto($con, $nombre, $descripcion, $precio, $descuento, $id_categoria, $activo);
        if (!$nuevo_id) {
            $errors[] = "No fue posible guardar el producto.";
        }
    }
}

$categorias = listarCategorias($con);
?>
<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Agregar producto</h1>

        <?php mostrarMensajes($errors); ?>
        <?php if ($nuevo_id) { ?>
            <div class="alert alert-success">
                Producto guardado con el ID <strong><?php echo $nuevo_id; ?></strong>.
                Sube su foto en <code>image/productos/<?php echo $nuevo_id; ?>/principal.webp</code>.
            </div>
        <?php } ?>

        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3" action="agregar_producto.php" method="post" autocomplete="off">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" maxlength="200" required>
                    </div>
                    <div class="col-md-6">
                        <label for="id_categoria" class="form-label">Categoría</label>
                        <select name="id_categoria" id="id_categoria" class="form-control" required>
                            <option value="">Selecciona…</option>
                            <?php foreach ($categorias as $c) { ?>
                                <option value="<?php echo (int) $c['id']; ?>"><?php echo e($c['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" step="0.01" min="0" name="precio" id="precio" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="descuento" class="form-label">Descuento (%)</label>
                        <input type="number" min="0" max="100" step="1" name="descuento" id="descuento" class="form-control" value="0">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" checked>
                            <label class="form-check-label" for="activo">Visible en la tienda</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar producto</button>
                        <a href="inicio.php" class="btn btn-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
