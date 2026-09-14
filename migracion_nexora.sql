-- ============================================================
-- NEXORA - migracion correctiva
-- Ejecutar sobre la base de datos 'tienda_ropa' YA IMPORTADA.
--
-- El proyecto original consultaba dos tablas que nunca se crearon:
--   * admin       -> el login del panel tronaba con error 500
--   * categorias  -> productos.id_categoria no apuntaba a ningun lado
-- ============================================================

-- Tabla de administradores del panel
CREATE TABLE IF NOT EXISTS `admin` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `usuario`    varchar(30)  NOT NULL,
  `password`   varchar(255) NOT NULL,
  `nombre`     varchar(80)  NOT NULL,
  `email`      varchar(80)  DEFAULT NULL,
  `activo`     tinyint(1)   NOT NULL DEFAULT 1,
  `fecha_alta` datetime     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Usuario inicial:  admin  /  nexora2026
-- IMPORTANTE: cambia esta contrasena antes de entregar o publicar.
INSERT INTO `admin` (`usuario`, `password`, `nombre`, `email`, `activo`, `fecha_alta`)
SELECT 'admin', '$2y$10$uZcOppdCLx50IxXC/EHfDe7e/fzh/4ky7MfSA5n0RxhHZk5g5SR.W', 'Administrador', 'admin@nexora.mx', 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `admin` WHERE `usuario` = 'admin');

-- Tabla de categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id`     int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `activo` tinyint(1)  NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `categorias` (`id`, `nombre`, `activo`) VALUES
  (1, 'Ropa', 1),
  (2, 'Accesorios', 1),
  (3, 'Joyeria', 1)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- Indices que faltaban en las busquedas mas frecuentes
ALTER TABLE `productos` ADD INDEX `idx_activo` (`activo`);
ALTER TABLE `productos` ADD INDEX `idx_categoria` (`id_categoria`);
