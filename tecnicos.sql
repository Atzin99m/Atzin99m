-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-07-2025 a las 19:49:16
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
  `N° Dispositivo equipo` varchar(50) NOT NULL,
  `Refacciones` varchar(50) NOT NULL,
  `Estatus` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `no_empl`, `Nombre`, `turno`, `no_orden`, `Reccepcion`, `recep_maquina`, `Tipo`, `Hr_ini`, `Hr-Fin`, `time_area`, `time_total`, `t_extra`, `t_realizado`, `observaciones`, `N° Dispositivo equipo`, `Refacciones`, `Estatus`) VALUES
(1, 554438, 'Juan', 'Vespertino', 1, '2025-07-29 09:31:44', '2025-07-29 09:31:07', '', '09:37:00', '00:00:00', '00:00:00', 0, '', '', '0', '', '', '0'),
(2, 554438, 'Juan', 'Vespertino', 4, '2025-07-29 09:31:44', '2025-07-29 09:31:07', '', '09:37:00', '00:00:00', '00:00:00', 0, '', '', '0', '', '', '0'),
(3, 58227, 'Juan', 'Vespertino', 3, '2025-07-29 09:31:44', '2025-07-29 09:31:07', '', '09:37:00', '00:00:00', '00:00:00', 0, '', '', '0', '', '', '0');

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
