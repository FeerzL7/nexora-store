<footer class="pie" id="contacto">
    <div class="contenedor">
        <div class="pie__columnas">
            <div class="pie__marca">
                <img src="<?php echo recurso('image/logo-claro.png'); ?>" alt="NEXORA">
                <p class="pie__lema">Ropa, accesorios y joyería en un solo lugar. Elige, combina y hazlo tuyo.</p>
            </div>

            <div>
                <h4>Tienda</h4>
                <ul>
                    <li><a href="index.php?categoria=1#catalogo">Ropa</a></li>
                    <li><a href="index.php?categoria=2#catalogo">Accesorios</a></li>
                    <li><a href="index.php?categoria=3#catalogo">Joyería</a></li>
                    <li><a href="index.php#catalogo">Ver todo</a></li>
                </ul>
            </div>

            <div>
                <h4>Ayuda</h4>
                <ul>
                    <li>Envíos y entregas</li>
                    <li>Cambios y devoluciones</li>
                    <li>Guía de tallas</li>
                    <li>Preguntas frecuentes</li>
                </ul>
            </div>

            <div>
                <h4>Tu cuenta</h4>
                <ul>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Crear cuenta</a></li>
                    <li><a href="carrito.php">Tu carrito</a></li>
                </ul>
            </div>
        </div>

        <div class="pie__cierre">
            <p>NEXORA <?php echo date('Y'); ?></p>
            <p>Nueva Rosita, Coahuila, México</p>
        </div>
    </div>
</footer>

<div class="mensajito" id="mensajito" role="status" aria-live="polite">
    <i class="bx bx-check-circle"></i><span id="mensajito_texto"></span>
</div>
