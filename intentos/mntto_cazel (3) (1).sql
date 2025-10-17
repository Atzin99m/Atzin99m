-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-07-2025 a las 23:53:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mntto_cazel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_trabajo`
--

CREATE TABLE `orden_trabajo` (
  `id_documento` int(11) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `maquina` varchar(50) NOT NULL,
  `seccion` varchar(50) NOT NULL,
  `solicitante` varchar(50) NOT NULL,
  `autorizacion` varchar(55) NOT NULL,
  `descripcion` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `fh_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `fh_recepcion` datetime DEFAULT NULL,
  `tiempo_estimado` time DEFAULT NULL,
  `tE_maq` time NOT NULL,
  `t_extra` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `tecnico` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `prioridad` varchar(20) NOT NULL,
  `paro` tinyint(1) NOT NULL DEFAULT 0,
  `tp_area` time DEFAULT NULL,
  `temp_paro` time NOT NULL DEFAULT current_timestamp(),
  `fh_maquina` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id_documento`, `departamento`, `maquina`, `seccion`, `solicitante`, `autorizacion`, `descripcion`, `status`, `fh_emision`, `fh_recepcion`, `tiempo_estimado`, `tE_maq`, `t_extra`, `observaciones`, `tecnico`, `tipo`, `prioridad`, `paro`, `tp_area`, `temp_paro`, `fh_maquina`) VALUES
(1, 'Sistemas', 'N/A', 'Oficina', 'Mauricio', 'Carlos Villaseñor', 'Checar puerta de Gerencia de Sistemas', 'Autorizado', '2025-06-23 21:00:00', NULL, NULL, '00:00:00', 0, 'Ninguna', NULL, '0', 'Urgente', 0, NULL, '00:00:00', '0000-00-00 00:00:00'),
(2, 'Sistemas', 'N/A', 'Nave 3', 'Sergio Guevara', 'Carlos Villaseñor', 'Cambiar canaletas de red', 'Autorizado', '2025-06-23 11:21:00', NULL, NULL, '00:00:00', 0, 'ninguna', NULL, '0', 'Normal', 0, NULL, '11:22:24', '2025-06-23 11:22:24'),
(3, 'Ingenieria', 'N/A', 'Nave 3', 'Carmen', '0', 'checar luz de oficina', 'Pendiente', '2025-06-23 14:31:00', NULL, NULL, '00:00:00', 0, 'N/A', NULL, '0', 'Urgente', 0, NULL, '14:32:21', '2025-06-23 14:32:21'),
(4, 'Sistemas', 'N/A', 'Nave 3', 'Sergio Guevara', '0', 'hhhhhh', 'Autorizado', '2025-06-24 10:31:00', NULL, NULL, '00:00:00', 0, 'n/a', NULL, '0', 'Urgente', 0, NULL, '10:33:06', '2025-06-24 10:33:06'),
(5, 'Mantenimiento', '313', 'Unidad de Inyección', 'Hugo', '0', 'hhhhh', 'Pendiente', '2025-06-27 12:20:00', NULL, NULL, '00:00:00', 0, 'hhh', NULL, '0', 'Urgente', 0, NULL, '12:43:50', '2025-06-27 12:43:50'),
(6, 'Mantenimiento', '107C', 'Remolido', 'Yohnny', '0', 'kkkk', 'Pendiente', '2025-07-14 12:42:00', NULL, NULL, '00:00:00', 0, 'N/A', NULL, '0', 'Urgente', 1, NULL, '12:44:48', '2025-07-14 12:44:48'),
(7, 'Mantenimiento', '107C', 'Remolido', 'Yohnny', '0', 'kkkk', 'Pendiente', '2025-07-14 12:42:00', NULL, NULL, '00:00:00', 0, 'N/A', NULL, '0', 'Urgente', 1, NULL, '12:47:40', '2025-07-14 12:47:40'),
(8, 'Mantenimiento', '107C', 'Remolido', 'Yohnny', '0', 'kkkk', 'Pendiente', '2025-07-14 12:42:00', NULL, NULL, '00:00:00', 0, 'N/A', NULL, '0', 'Urgente', 1, NULL, '12:52:37', '2025-07-14 12:52:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `no_empl` double NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `turno` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `no_empl`, `Nombre`, `turno`) VALUES
(1, 1, 'Giovanni', 'vespertino'),
(2, 2, 'Ricardo', 'Vespertino'),
(3, 3, 'Fernando', 'matutino'),
(4, 4, 'Noe', 'matutino'),
(5, 5, 'Juan', 'mixto'),
(6, 6, 'Diego', 'Matutino');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `tecnico` (`tecnico`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD CONSTRAINT `orden_trabajo_ibfk_1` FOREIGN KEY (`tecnico`) REFERENCES `tecnicos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
