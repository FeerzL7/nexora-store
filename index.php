<?php 
require 'config/config.php';
require 'config/basededatos.php';
$db = new Database();
$con = $db->conectar();
$sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
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
    <section class="main-home">
        <div class="main-text">
            <h5>NEXORA</h5>
            <h1>Nueva Coleccion<br> Deportiva</h1>
            <p>Viste tu pasión, vive tu estilo: NEXORA, donde la moda y el deporte se encuentran.</p>

            <a href="#trending" class="main-btn">Shp Now <i class="bx bx-right-arrow-alt"></i></a>
        </div>
        <div class="down-arrow">
            <a href="#trending" class="down"><i class="bx bx-down-arrow-alt"></i></a>
        </div>
    </section>
    <!--trending-products-section-->
    <section class="trending-product" id="trending">
        <div class="center-text">
            <h2>Our Trending <span>Products</span></h2>
        </div>
        <div class="products">
        <?php foreach($resultado as $row) { ?>
            <div class="row">
                <?php 
                    $id = $row['id'];
                    $imagen = "image/productos/" . $id . "/principal.webp";
                    if(!file_exists($imagen)){
                        $imagen = "images/no-photo.jpg";
                    }
                ?>
                <img src="<?php echo $imagen; ?>" alt="">
                <div class="product-text">
                    <h5>-%<?php echo $row['descuento']; ?></h5>
                </div>
                <div class="heart-icon">
                    <button type="button" onclick="addProducto(<?php echo $row['id']; ?>)"><i class="bx bx-heart"></i></button>
                </div>
                <div class="price">
                    <a href="detalles.php?id=<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></a>
                    <p>$<?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                </div>
            </div>
        <?php } ?>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>