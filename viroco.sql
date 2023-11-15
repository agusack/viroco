-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-09-2023 a las 23:53:51
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `viroco`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristicas`
--

CREATE TABLE `caracteristicas` (
  `id_caracteristica` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `valor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caracteristicas`
--

INSERT INTO `caracteristicas` (`id_caracteristica`, `id_producto`, `nombre`, `valor`) VALUES
(3, 2, 'color', 'rojo'),
(4, 2, 'tamaño', 'L'),
(5, 2, 'modelo', 'A'),
(7, 24, 'Color', 'Negro'),
(8, 24, 'Talla ', 'M'),
(9, 25, 'Color', 'Negro'),
(23, 1, 'Color', 'blanco'),
(24, 1, 'Color', 'negro'),
(25, 1, 'COLOR', 'negro'),
(26, 29, 'Color', 'Blanco'),
(27, 29, 'Color', 'Negro'),
(28, 29, 'Talla', 'L'),
(29, 29, 'Talla', 'M'),
(34, 31, 'Color', 'rojo'),
(35, 31, 'Color', 'negro'),
(36, 31, 'Color', 'blanco'),
(37, 31, 'Talle', 'L'),
(38, 31, 'Talle', 'L'),
(47, 29, 'Color', 'Violeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'Moda'),
(2, 'Tecnología'),
(3, 'Hogar'),
(7, 'Bazar'),
(8, 'Deco'),
(9, 'Prueba1'),
(10, 'Prueba2'),
(11, 'Bazar2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combinaciones_producto`
--

CREATE TABLE `combinaciones_producto` (
  `id_combinacion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `combinacion_unica` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combinaciones_producto`
--

INSERT INTO `combinaciones_producto` (`id_combinacion`, `id_producto`, `combinacion_unica`, `stock`) VALUES
(1, 29, 'Blanco - L', 20),
(2, 29, 'Negro - L', 19),
(3, 29, 'Negro - M', 19),
(4, 29, 'Blanco - M', 19),
(7, 31, 'rojo - L', 10),
(8, 31, 'rojo - L', 10),
(9, 32, 'Negro - M', 10),
(10, 32, 'blanco - M', 5),
(11, 29, 'Violeta - M', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(30) DEFAULT NULL,
  `correo_usuario` varchar(30) DEFAULT NULL,
  `celular` varchar(30) NOT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `entrega` text DEFAULT NULL,
  `metodo_pago` varchar(30) NOT NULL,
  `productos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `nombre_usuario`, `correo_usuario`, `celular`, `fecha_pedido`, `total`, `entrega`, `metodo_pago`, `productos`) VALUES
(20, 2, 'agustin', 'admin@admin.com', '13442', '2023-09-23 12:07:06', 101010.00, '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asd\",\"DNI\":\"123\"}', 'efectivo', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":\"29_Blanco_L\"}]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_categoria` int(4) DEFAULT NULL,
  `id_subcategoria` int(4) NOT NULL,
  `stock` int(4) NOT NULL,
  `popular` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `imagen2` text NOT NULL,
  `imagen3` text NOT NULL,
  `imagen4` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `id_categoria`, `id_subcategoria`, `stock`, `popular`, `imagen`, `imagen2`, `imagen3`, `imagen4`) VALUES
(1, 'Campera Negra con plumas', 'campera negra', 30.00, 1, 1, 22, 135, '/Viroco/img/productos/iphone7.jpg', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(2, 'Remera roja', 'remera roja', 29.99, 1, 2, 0, 20, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(3, 'Iphone 7', 'iphone 7', 99.99, 2, 3, 0, 8, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(4, 'notebook HP', 'Descripción del producto de tecnología 2', 149.99, 2, 4, 0, 1, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(5, 'Sillon L', 'sillon L', 49.99, 3, 5, 0, 8, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(6, 'Cama 2 plazas', 'Descripción del producto de hogar 2', 79.99, 3, 6, 1, 4, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(18, 'asd', 'qwe', 236.00, 1, 2, 0, 2, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(19, 'asd', 'seba puto', 111.00, 2, 3, 0, 0, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(21, 'prueba1 iphone', 'iphone', 123123.00, 2, 3, 0, 0, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(22, 'prueba 2', 'asd', 1231.00, 1, 12, 0, 0, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(23, 'prueba', 'asdasd', 123.00, 2, 4, 0, 7, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/foto_producto.png'),
(24, 'prueba caracteristica', 'prueba', 1231.00, 8, 15, 12, 0, '/Viroco/img/productos/foto_producto.png', '/Viroco/img/productos/iphone7.jpg', '', ''),
(25, 'prueba caracteristica2', 'asd', 236.00, 8, 16, 22, 2, '/Viroco/img/productos/iphone7.jpg', '', '', ''),
(29, 'para borrar', 'probando', 101010.00, 2, 4, 96, 54, '/Viroco/img/productos/foto_producto.png', '', '', ''),
(31, 'compu', 'compu ga', 213432.00, 2, 3, 20, 0, '/Viroco/img/productos/foto_producto.png', '', '', ''),
(32, 'prueba stock', 'asd', 123.00, 11, 19, 15, 0, '/Viroco/img/productos/i.jpg', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas_stock`
--

CREATE TABLE `reservas_stock` (
  `id_reserva` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `combinacion_unica` varchar(255) DEFAULT NULL,
  `cantidad_reservada` int(11) NOT NULL,
  `hora_reserva` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategorias`
--

CREATE TABLE `subcategorias` (
  `id_subcategoria` int(11) NOT NULL,
  `nombre_subcategoria` varchar(50) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`id_subcategoria`, `nombre_subcategoria`, `id_categoria`) VALUES
(1, 'Camperas', 1),
(2, 'Remeras', 1),
(3, 'Telefonos', 2),
(4, 'Notebooks', 2),
(5, 'Sillones', 3),
(6, 'Camas', 3),
(12, 'asd', 1),
(13, 'Bazar 1', 7),
(14, 'Bazar 2', 7),
(15, 'Deco 1', 8),
(16, 'Deco 2', 8),
(17, 'Deco 3', 8),
(18, 'Deco 4', 8),
(19, 'Tazas', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `correo`, `password`, `is_admin`) VALUES
(2, 'agustin', 'admin@admin.com', '$2y$10$r6SxrsSmTXQz6XEA70BZfe2zlBg7a16Dvhh3nI7BU1jhqGE1uD/lO', 1),
(3, 'tutuca', 'agus@hotmail.com', '$2y$10$t1FZ9D8iRYThqtaJzhzN8.xM8FSfyLm3KJiX8QZ36n1IXFsr8XZp6', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(255) DEFAULT NULL,
  `correo_usuario` varchar(255) DEFAULT NULL,
  `fecha_venta` datetime DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `entrega` text NOT NULL,
  `productos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_usuario`, `nombre_usuario`, `correo_usuario`, `fecha_venta`, `total`, `estado`, `entrega`, `productos`) VALUES
(6, 2, 'agustin', 'admin@admin.com', '2023-09-13 23:11:21', 101010.00, 'Aprobado', '{\"Entrega\":\"Envio a domicilio\",\"Nombre\":\"Agustin Ackerman\",\"Telefono\":\"1234567890\",\"Ciudad\":\"rg\",\"Calle\":\"oh\",\"Altura\":\"123\",\"Depto\":\"1234\",\"Codigo Postal\":\"4321\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(7, 2, 'agustin', 'admin@admin.com', '2023-09-13 23:16:13', 101010.00, 'Aprobado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdasd\",\"DNI\":\"3121321\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(8, 2, 'agustin', 'admin@admin.com', '2023-09-13 23:16:21', 202020.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdasd\",\"DNI\":\"123123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":2,\"id_producto\":29}]'),
(9, 2, 'agustin', 'admin@admin.com', '2023-09-14 11:14:15', 101010.00, 'Aprobado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"Agustin Ackerman\",\"DNI\":\"41402558\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(10, 2, 'agustin', 'admin@admin.com', '2023-09-14 11:15:02', 101010.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdasd\",\"DNI\":\"123123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(11, 2, 'agustin', 'admin@admin.com', '2023-09-14 20:47:41', 101010.00, 'Aprobado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdasd\",\"DNI\":\"123123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(12, 2, 'agustin', 'admin@admin.com', '2023-09-14 20:47:47', 101010.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdasd\",\"DNI\":\"123123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(13, 2, 'agustin', 'admin@admin.com', '2023-09-15 22:12:38', 101010.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdasd\",\"DNI\":\"123123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(14, 2, 'agustin', 'admin@admin.com', '2023-09-15 22:54:34', 202020.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asd\",\"DNI\":\"123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":\"29_Blanco_L\"},{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Negro\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":\"29_Negro_L\"}]'),
(15, 2, 'agustin', 'admin@admin.com', '2023-09-15 22:55:53', 101010.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asd\",\"DNI\":\"123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(16, 2, 'agustin', 'admin@admin.com', '2023-09-15 23:37:53', 101010.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asdsasd\",\"DNI\":\"321231\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(17, 2, 'agustin', 'admin@admin.com', '2023-09-18 15:41:33', 101010.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asd\",\"DNI\":\"123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":29}]'),
(18, 2, 'agustin', 'admin@admin.com', '2023-09-18 16:25:14', 303030.00, 'Rechazado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asd\",\"DNI\":\"123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"1\",\"id_producto\":\"29_Blanco_L\"},{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Negro\",\"Talla\":\"L\"},\"cantidad\":\"2\",\"id_producto\":\"29_Negro_L\"}]'),
(19, 2, 'agustin', 'admin@admin.com', '2023-09-23 12:07:44', 202020.00, 'Aprobado', '{\"Entrega\":\"Retiro por el local\",\"Nombre\":\"asd\",\"DNI\":\"123\"}', '[{\"producto\":\"para borrar\",\"caracteristicas\":{\"Color\":\"Blanco\",\"Talla\":\"L\"},\"cantidad\":\"2\",\"id_producto\":\"29_Blanco_L\"}]');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  ADD PRIMARY KEY (`id_caracteristica`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `combinaciones_producto`
--
ALTER TABLE `combinaciones_producto`
  ADD PRIMARY KEY (`id_combinacion`),
  ADD KEY `producto_id` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas_stock`
--
ALTER TABLE `reservas_stock`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD PRIMARY KEY (`id_subcategoria`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  MODIFY `id_caracteristica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `combinaciones_producto`
--
ALTER TABLE `combinaciones_producto`
  MODIFY `id_combinacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `reservas_stock`
--
ALTER TABLE `reservas_stock`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  MODIFY `id_subcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `combinaciones_producto`
--
ALTER TABLE `combinaciones_producto`
  ADD CONSTRAINT `combinaciones_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `reservas_stock`
--
ALTER TABLE `reservas_stock`
  ADD CONSTRAINT `reservas_stock_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD CONSTRAINT `subcategorias_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
