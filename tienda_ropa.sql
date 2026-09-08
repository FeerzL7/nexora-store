-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-11-2023 a las 20:19:57
-- Versión del servidor: 5.7.11
-- Versión de PHP: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_ropa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombres` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` tinyint(4) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modifica` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombres`, `apellidos`, `email`, `telefono`, `estatus`, `fecha_alta`, `fecha_modifica`, `fecha_baja`) VALUES
(1, 'Hepsiba', 'Aguilar', 'hepsibaaguilar@gmail.com', '861151150', 1, '2023-11-25 20:29:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` tinyint(3) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `descuento`, `id_categoria`, `activo`) VALUES
(1, 'Zapatillas de running Nike Air Zoom Pegasus 39\n', 'Zapatillas de running para hombre y mujer con amortiguación ligera y respuesta rápida. Cuentan con la tecnología Air Zoom en el talón para una mayor absorción de impactos, y la suela Zoom Air en la puntera para una mayor propulsión.\n', '1500.00', 20, 1, 1),
(2, 'Zapatillas de baloncesto Nike Air Jordan 1 Mid\n', 'Zapatillas de baloncesto para hombre y mujer con un diseño clásico y elegante. Cuentan con la tecnología Air Jordan en la suela para una mayor amortiguación y estabilidad.\n', '2000.00', 15, 1, 1),
(3, 'Zapatillas de tenis Adidas Adizero Ubersonic 4\r\n', 'Zapatillas de tenis para hombre y mujer con un diseño ligero y transpirable. Cuentan con la tecnología Adizero para una mayor velocidad y agilidad.\r\n', '1800.00', 10, 1, 1),
(4, 'Pantalón corto de entrenamiento Nike Dri-FIT\r\n', 'Pantalón corto de entrenamiento para hombre y mujer con un diseño cómodo y transpirable. Cuenta con la tecnología Dri-FIT para absorber el sudor y mantenerte seco.\r\n', '750.00', 25, 2, 1),
(5, 'Camiseta de compresión Nike Pro\r\n', 'Camiseta de compresión para hombre y mujer con un diseño ligero y ajustado. Cuenta con la tecnología Dri-FIT para absorber el sudor y mantenerte seco.\r\n', '500.00', 20, 2, 1),
(6, 'Sudadera con capucha Nike Sportswear\r\n', 'Sudadera con capucha para hombre y mujer con un diseño cómodo y estiloso. Cuenta con la tecnología Therma-FIT para mantenerte abrigado.\r\n', '1200.00', 15, 2, 1),
(7, 'Gorra Nike Air Jordan\r\n', 'Gorra para hombre y mujer con un diseño clásico y elegante. Cuenta con el logo Air Jordan bordado en la parte delantera.\r\n', '500.00', 20, 3, 1),
(8, 'Calcetines Nike Dri-FIT\r\n', 'Calcetines para hombre y mujer con un diseño cómodo y transpirable. Cuentan con la tecnología Dri-FIT para absorber el sudor y mantenerte seco.\r\n', '300.00', 15, 3, 1),
(9, 'Banda para el sudor Nike\r\n', 'Banda para el sudor para hombre y mujer con un diseño cómodo y funcional. Ayuda a absorber el sudor y mantenerte seco.\r\n', '200.00', 10, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `activacion` int(11) NOT NULL DEFAULT '0',
  `token` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `token_password` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `password_request` int(11) NOT NULL DEFAULT '0',
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `activacion`, `token`, `token_password`, `password_request`, `id_cliente`) VALUES
(1, 'Hepsiba', '$2y$10$FFiXYOaFiHq.2sCgRcVAQOoz0kL0CyA8ZH3g9C.kc.WrCuT1980D6', 1, '50b557dcd98a5068db32f7404e006ab7', NULL, 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
