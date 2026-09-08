<header>
    <a href="" class="logo"><img src="image/logo.png" alt=""></a>
    <ul class="navmenu">
        <li><a href="index.php">Inicio</a></li>
        <li><a href="#trending">Productos</a></li>
        <li><a href="#info">Info</a></li>
    </ul>
    <div class="nav-icon">
        <?php if (isset($_SESSION['user_id'])) { ?>
                <a class="lgn" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-user"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="btn_session">
                    <a href="logout.php" class="dropdown-item">Cerrar Sesion</a>
                </ul>
        <?php } else { ?>
            <a href="login.php"><i class="bx bx-user"></i></a>
        <?php } ?>
        <a href="carrito.php"><i class="bx bx-cart"></i><span id="num_cart"></span></a>
        <div class="bx bx-menu" id="menu-icon"></div>
    </div>
</header>