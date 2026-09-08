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
    <title>SportZone</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="image/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <section>
        <table class="tabla">
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
                                <button id="eliminar"data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal" onclick="mostrarModal()" class="btn">Eliminar</button>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">
                            <h3 id="total"><?php echo PRECIO . number_format($total, 2, '.', ','); ?></h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php if ($lista_carrito != null) { ?>
                            <div class="button-container">
                                <?php if (isset($_SESSION['user_cliente'])) { ?>
                                    <a href="realizarpago.php" class="btn">Realizar Pago</a>
                                <?php } else { ?>
                                    <a href="login.php?pago" class="btn">Realizar Pago</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        </td>
                    </tr>
            </tbody>
        <?php } ?>
        </table>
        <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Desea eliminar el producto de la lista?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btn-elimina" type="button" class="btn" onclick="eliminar()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        let eliminaModal = document.getElementById("eliminaModal");
        eliminaModal.addEventListener("show.bs.modal", function(event) {
            let button = event.relatedTarge;
            let id = button.getAttribute("data-bs-id");
            let buttonElimina = eliminaModal.querySelector(".modal-footer #btn-elimina");
            buttonElimina.value = id;
        });
    </script>
</body>

</html>