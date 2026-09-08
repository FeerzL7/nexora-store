<?php
require 'config/config.php';
require 'config/basededatos.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$lista_carrito = array();
if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXORA</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="image/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <section>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($lista_carrito == null) {
                    echo '<tr><td colspan="5" class="text-center"><b>lista Vacia</b></td></tr>';
                } else {
                    $total = 0;
                    foreach ($lista_carrito as $producto) {
                        $_id = $producto['id'];
                        $nombre = $producto['nombre'];
                        $precio = $producto['precio'];
                        $subtotal = $cantidad * $precio;
                        $total += $subtotal;
                ?>

                        <tr>
                            <td><?php echo $nombre; ?></td>
                            <td><?php echo PRECIO . number_format($precio, 2, '.', ','); ?></td>
                            <td>
                                <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                            </td>
                            <td>
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                    <?php echo PRECIO . number_format($subtotal, 2, '.', ','); ?>
                                </div>
                            </td>
                            <td>
                                <a href="#" id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">
                            <h3 id="total"><?php echo PRECIO . number_format($total, 2, '.', ','); ?></h3>
                        </td>
                    </tr>
            </tbody>
            <?php if ($lista_carrito != null) { ?>
                <div class="button-container">
                    <?php if (isset($_SESSION['user_cliente'])) { ?>
                        <a href="pago.php" class="btn btn-primary btn-lg">Realizar Pago</a>
                    <?php } else { ?>
                        <a href="login.php?pago" class="btn">Realizar Pago</a>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
        </table>
        <div id="customModal" class="custom-modal">
            <div class="modal-content">
                <p>¿Estás seguro de que deseas eliminar este elemento?</p>
                <button class="btn-confirm" onclick="eliminar()">Eliminar</button>
                <button class="btn-cancel" onclick="ocultarModal()">Cancelar</button>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>