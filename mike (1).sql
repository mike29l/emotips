-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-05-2025 a las 01:24:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mike`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `respuestas` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`id`, `id_usuario`, `respuestas`, `fecha`) VALUES
(1, 2, '{\"1\":\"si\",\"2\":\"si\",\"3\":\"si\",\"4\":\"\"}', '2025-04-19 21:31:03'),
(2, 2, '{\"1\":\"si\",\"2\":\"si\",\"3\":\"si\",\"4\":\"si\"}', '2025-04-19 21:31:45'),
(3, 2, '{\"1\":\"nose\",\"2\":\"ok\",\"3\":\"yes\",\"4\":\"las dos\"}', '2025-04-19 21:56:02'),
(4, 2, '{\"1\":\"si\",\"2\":\"no\",\"3\":\"si\",\"4\":\"si\"}', '2025-04-26 15:01:41'),
(5, 14, '{\"1\":\"no\",\"2\":\"si\",\"3\":\"si\",\"4\":\"no\"}', '2025-04-30 19:36:51'),
(6, 14, '{\"1\":\"si\",\"2\":\"no\",\"3\":\"si\",\"4\":\"no\"}', '2025-04-30 20:27:21'),
(7, 16, '{\"1\":\"no\",\"2\":\"me cuesta dormir\",\"3\":\"si\",\"4\":\"no tengo esperanza\"}', '2025-04-30 20:29:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `verification_sid` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `verificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `telefono`, `verification_sid`, `fecha_creacion`, `verificado`) VALUES
(2, '+529611885426', 'VE4a0900d8546684a45298dd1ce5a6ca32', '2025-05-03 09:05:21', 1),
(3, '+529611885426', 'VEe00c59ee320536c7ef3cc48c4d5aa287', '2025-05-03 09:30:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperaciones`
--

CREATE TABLE `recuperaciones` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  `expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `contrasenia` varchar(250) NOT NULL,
  `usuario` varchar(250) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `imagen_perfil` varchar(255) NOT NULL,
  `ultima_actualizacion_password` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`, `contrasenia`, `usuario`, `id_rol`, `imagen_perfil`, `ultima_actualizacion_password`) VALUES
(36, 'miguel angel corzo sanchez', '$2y$10$tTUzE0HSlpxQJCqA4bRXUO69o01nwkkV0dAXSf4u.CiElEpCtMcvm', 'mike', 2, '', '2025-05-31 01:23:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_telefonos`
--

CREATE TABLE `usuarios_telefonos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `codigo_verificacion` varchar(255) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_telefonos`
--

INSERT INTO `usuarios_telefonos` (`id`, `user_id`, `telefono`, `fecha_registro`, `codigo_verificacion`, `codigo_expira`) VALUES
(10, 14, '9612468430', '2025-05-02 04:48:37', NULL, NULL),
(0, 28, '9611885426', '2025-05-03 09:29:01', NULL, NULL),
(0, 29, '9612468430', '2025-05-08 01:40:25', NULL, NULL),
(0, 31, '9601172333', '2025-05-08 21:54:58', NULL, NULL),
(0, 32, '9681087513', '2025-05-08 22:56:28', NULL, NULL),
(0, 33, '9612468430', '2025-05-14 18:00:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE `visitas` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `pagina` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `visitas`
--

INSERT INTO `visitas` (`id`, `usuario`, `pagina`, `fecha`) VALUES
(1, 'mike', 'cliente.php', '2025-05-14 12:18:33'),
(2, 'mike', 'cliente.php', '2025-05-14 12:19:25'),
(3, 'mike', 'cliente.php', '2025-05-14 12:28:14'),
(4, 'mike', 'cliente.php', '2025-05-14 12:37:12'),
(5, 'mike', 'cliente.php', '2025-05-14 12:37:34'),
(6, 'mike', 'cliente.php', '2025-05-14 14:41:50'),
(7, 'mike', 'cliente.php', '2025-05-14 14:42:00'),
(8, 'mike', 'cliente.php', '2025-05-19 16:12:52'),
(9, 'mike', 'cliente.php', '2025-05-19 16:13:06'),
(10, 'mike', 'cliente.php', '2025-05-19 16:20:15'),
(11, 'lalo', 'cliente.php', '2025-05-19 16:21:27'),
(12, 'mike', 'cliente.php', '2025-05-30 17:23:17');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `verification_sid` (`verification_sid`);

--
-- Indices de la tabla `recuperaciones`
--
ALTER TABLE `recuperaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `recuperaciones`
--
ALTER TABLE `recuperaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
