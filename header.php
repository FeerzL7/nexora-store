<header class="encabezado" id="encabezado">
    <a href="index.php" class="encabezado__logo" aria-label="NEXORA, inicio">
        <img src="<?php echo recurso('image/logo.png'); ?>" alt="NEXORA">
    </a>

    <nav>
        <ul class="menu" id="menu">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="index.php#categorias">Categorías</a></li>
            <li><a href="index.php#catalogo">Catálogo</a></li>
            <li><a href="index.php#contacto">Contacto</a></li>
        </ul>
    </nav>

    <div class="acciones">
        <?php if (isset($_SESSION['user_cliente'])) { ?>
            <div class="sesion">
                <a role="button" tabindex="0" id="btn_session" aria-expanded="false"
                   aria-haspopup="true" aria-label="Tu cuenta"><i class="bx bx-user"></i></a>
                <ul class="sesion__menu" id="menu_sesion">
                    <li><a href="carrito.php">Tu carrito</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        <?php } else { ?>
            <a href="login.php" aria-label="Iniciar sesión"><i class="bx bx-user"></i></a>
        <?php } ?>

        <a href="carrito.php" aria-label="Ver tu carrito">
            <i class="bx bx-shopping-bag"></i>
            <span class="contador" id="num_cart"><?php echo (int) ($num_cart ?? 0); ?></span>
        </a>

        <button class="hamburguesa bx bx-menu" id="menu-icon" aria-label="Abrir menú" aria-expanded="false"></button>
    </div>
</header>
