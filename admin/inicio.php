<?php
require 'back/sesion.php';
require 'config/basededatos.php';
require 'back/adminFunciones.php';

$db  = new Database();
$con = $db->conectar();

$productos = listarProductos($con);
?>
<?php include 'header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Catálogo completo</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Desc.</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$productos) { ?>
                            <tr><td colspan="7">Todavía no hay productos registrados.</td></tr>
                        <?php } ?>
                        <?php foreach ($productos as $p) { ?>
                            <tr>
                                <td><?php echo (int) $p['id']; ?></td>
                                <td><?php echo e($p['nombre']); ?></td>
                                <td><?php echo e($p['categoria']); ?></td>
                                <td><?php echo PRECIO . number_format($p['precio'], 2, '.', ','); ?></td>
                                <td><?php echo (int) $p['descuento']; ?>%</td>
                                <td>
                                    <?php if ($p['activo']) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary"
                                       href="actualizar_producto.php?id=<?php echo (int) $p['id']; ?>">Editar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
