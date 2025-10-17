-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-07-2025 a las 19:50:22
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
-- Estructura de tabla para la tabla `autorizacion`
--

CREATE TABLE `autorizacion` (
  `id` int(11) NOT NULL,
  `no_empl` bigint(20) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `passw` varchar(20) NOT NULL,
  `Area` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autorizacion`
--

INSERT INTO `autorizacion` (`id`, `no_empl`, `Nombre`, `passw`, `Area`) VALUES
(1, 3038, 'Carlos Villaseñor', 'CV6865', 'Sistemas'),
(2, 5555, 'Hugo fonseca', 'HF5636', 'Mantenimiento'),
(3, 6668, 'Jorge', 'J8963', 'Ingenieria'),
(4, 56, 'Ericka Rangel', 'er57', 'Direccion'),
(5, 57, 'Luis Ayala', 'LA557', 'Operaciones'),
(6, 56, 'Melanie SanJuan', 'er56', 'Moldes'),
(7, 3040, 'Roberto Olvera', 'RV6865', 'Finanzas'),
(8, 3039, 'Daniel Contreras', 'CD6866', 'Mejora Continua');

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
  `Estatus` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `fh_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `prioridad` varchar(20) NOT NULL,
  `paro` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id_documento`, `departamento`, `maquina`, `seccion`, `solicitante`, `autorizacion`, `descripcion`, `Estatus`, `fh_emision`, `observaciones`, `prioridad`, `paro`) VALUES
(1, 'Sistemas', 'N/A', 'Oficina', 'Mauricio', 'Carlos Villaseñor', 'Checar puerta de Gerencia de Sistemas', 'Autorizado', '2025-06-23 21:00:00', 'Ninguna', 'Urgente', 0),
(2, 'Sistemas', 'N/A', 'Nave 3', 'Sergio Guevara', 'Carlos Villaseñor', 'Cambiar canaletas de red', 'Autorizado', '2025-06-23 11:21:00', 'ninguna', 'Normal', 0),
(3, 'Ingenieria', 'N/A', 'Nave 3', 'Carmen', '0', 'checar luz de oficina', 'Autorizado', '2025-06-23 14:31:00', 'N/A', 'Urgente', 0),
(4, 'Sistemas', 'N/A', 'Nave 3', 'Sergio Guevara', '0', 'hhhhhh', 'Autorizado', '2025-06-24 10:31:00', 'n/a', 'Urgente', 0),
(5, 'Mantenimiento', '313', 'Unidad de Inyección', 'Hugo', '0', 'hhhhh', 'Pendiente', '2025-06-27 12:20:00', 'hhh', 'Urgente', 0),
(6, 'Mantenimiento', '107C', 'Remolido', 'Yohnny', '0', 'kkkk', 'Pendiente', '2025-07-14 12:42:00', 'N/A', 'Urgente', 1),
(7, 'Mantenimiento', '107C', 'Remolido', 'Yohnny', '0', 'kkkk', 'Pendiente', '2025-07-14 12:42:00', 'N/A', 'Urgente', 1),
(8, 'Mantenimiento', '107C', 'Remolido', 'Yohnny', '0', 'kkkk', 'Pendiente', '2025-07-14 12:42:00', 'N/A', 'Urgente', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `no_empl` double NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `turno` varchar(10) NOT NULL,
  `no_orden` int(50) NOT NULL,
  `Reccepcion` datetime NOT NULL DEFAULT current_timestamp(),
  `recep_maquina` datetime NOT NULL DEFAULT current_timestamp(),
  `Tipo` varchar(55) NOT NULL,
  `Hr_ini` time NOT NULL,
  `Hr-Fin` time NOT NULL,
  `time_area` time NOT NULL,
  `time_total` int(11) NOT NULL,
  `t_extra` varchar(55) NOT NULL,
  `t_realizado` varchar(50) NOT NULL,
  `observaciones` varchar(11) NOT NULL,

  `Refacciones` varchar(50) NOT NULL,
  `Estatus` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `no_empl`, `Nombre`, `turno`, `no_orden`, `Reccepcion`, `recep_maquina`, `Tipo`, `Hr_ini`, `Hr-Fin`, `time_area`, `time_total`, `t_extra`, `t_realizado`, `observaciones`, `Refacciones`, `Estatus`) VALUES
(1, 554438, 'Juan', 'Vespertino', 1, '2025-07-29 09:31:44', '2025-07-29 09:31:07', '', '09:37:00', '00:00:00', '00:00:00', 0, '', '', '0', '',  '0'),
(2, 554438, 'Juan', 'Vespertino', 4, '2025-07-29 09:31:44', '2025-07-29 09:31:07', '', '09:37:00', '00:00:00', '00:00:00', 0, '', '', '0', '',  '0'),
(3, 58227, 'Juan', 'Vespertino', 3, '2025-07-29 09:31:44', '2025-07-29 09:31:07', '', '09:37:00', '00:00:00', '00:00:00', 0, '', '', '0', '',  '0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autorizacion`
--
ALTER TABLE `autorizacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`id_documento`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `relacion` (`no_orden`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autorizacion`
--
ALTER TABLE `autorizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
