<?php
require 'config/config.php';
require 'config/basededatos.php';
$db = new Database();
$con = $db->conectar();
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '') {
    echo 'Error al procesar la peticion';
    exit;
} else {
    $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
    $sql->execute([$id]);
    if ($sql->fetchColumn() > 0) {
        $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1
        LIMIT 1");
        $sql->execute([$id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
        $precio = $row['precio'];
        $descuento = $row['descuento'];
        $precio_desc = $precio - (($precio * $descuento) / 100);
        $dir_images = 'image/productos/' . $id . '/';

        $rutaImg = $dir_images . 'principal.webp';

        if (!file_exists($rutaImg)) {
            $rutaImg = 'image/no-photo.webp';
        }
        $images = array();
        if (file_exists($dir_images)) {
            $dir = dir($dir_images);

            while (($archivo = $dir->read()) != false) {
                if ($archivo != 'principal.webp' && (strpos($archivo, 'webp') || strpos($archivo, 'webp'))) {
                    $imagenes[] = $dir_images . $archivo;
                }
            }
        }
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="detalles-content container">
        <div class="detalles-img">
            <img src="<?php echo $rutaImg; ?>" alt="">
        </div>
        <div class="detalles-txt">
            <h4><?php echo $nombre; ?></h4>
            <?php if ($descuento > 0) { ?>
                <p><del><?php echo PRECIO . number_format($precio, 2, '.', ','); ?></del></p>
                <h4>
                    <?php echo PRECIO . number_format($precio_desc, 2, '.', ','); ?>
                    <small class="text-success"><?php echo $descuento; ?>% descuento</small>
                </h4>
            <?php } else { ?>
                <h4><?php echo PRECIO . number_format($precio, 2, '.', ','); ?></h4>
            <?php } ?>
            <p>
                <?php echo $descripcion ?>
            </p>
            <button class="boton-2" type="button" onclick="addProducto(<?php echo $id; ?>)">Agregar al Carrito</button>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>