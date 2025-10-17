-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2025 a las 22:37:36
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
-- Base de datos: `cazel_all`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_trabajo`
--

CREATE TABLE `orden_trabajo` (
  `id_documento` int(11) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `maquina` varchar(50) NOT NULL,
  `solicitante` varchar(50) NOT NULL,
  `Estatus` varchar(50) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `fh_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `paro` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id_documento`, `departamento`, `maquina`, `solicitante`, `Estatus`, `descripcion`, `fh_emision`, `paro`) VALUES
(1, 'Ingenieria', '301', 'Cesar', 'Autorizado', 'joijojeo|nionionoj', '2025-08-07 12:38:00', 'si'),
(2, 'Sistemas', 'N/A', 'Carlos', 'Autorizado', 'gawgwwar', '2025-07-21 13:47:00', 'no'),
(3, 'Ingenieria', 'N/A', 'Fernanda', 'Autorizado', 'gigiougiouiyñi', '2025-08-07 11:26:00', 'si'),
(4, 'Operaciones', '107', 'Linda', 'Pendiente', 'checar molde', '2025-08-11 14:39:00', 'si'),
(5, 'Operaciones', '219A', 'Carlos', 'Pendiente', 'Revisar fuga de aceite', '2025-09-23 09:58:00', 'si'),
(6, 'Operaciones', '108C', 'Carlos', 'Pendiente', 'deded', '2025-09-23 14:30:00', 'si'),
(7, 'Operaciones', 'N/A', 'fefef', 'Pendiente', 'efe', '2025-09-23 14:31:00', 'si'),
(8, 'Produccion', 'N/A', 'Geraldine', 'Pendiente', 'skncis', '0000-00-00 00:00:00', 'si'),
(9, 'Produccion', '109 B.', 'Rafael', 'Pendiente', 'boioguo', '2025-09-23 14:46:00', 'si'),
(10, 'Operaciones', '111A', 'Melanie Ordoñez', 'Pendiente', 'ceec', '2025-09-23 15:47:00', 'si'),
(11, 'Operaciones', '111A', 'Atzin Luna', 'Pendiente', 'ecineincqinc', '2025-09-23 16:00:00', 'si'),
(12, 'Produccion', '216', 'Luis', 'Pendiente', 'moemfoeonfeonf', '2025-09-24 09:07:00', 'si'),
(13, 'Operaciones', '107C', 'Andres', 'Pendiente', 'Sobrecalentamiento de regulador', '2025-09-24 09:17:00', 'si'),
(14, 'Moldes', '106', 'Fernanda', 'Pendiente', 'Molde atorado', '2025-09-24 09:23:00', 'si'),
(15, 'Operaciones', '310', 'Majo', 'Pendiente', 'Falla en el robot', '2025-09-24 09:30:00', 'si'),
(16, 'Produccion', '106', 'Bruno', 'Pendiente', 'Fuga de aceite', '2025-09-24 09:38:00', 'si'),
(17, 'Operaciones', '105', 'Diana', 'Pendiente', 'mmm', '0000-00-00 00:00:00', 'no'),
(18, 'Produccion', '103', 'Andrea', 'Pendiente', 'kkdjosjd', '2025-09-24 10:00:00', 'si'),
(19, 'Operaciones', '219A', 'Majo', 'Pendiente', 'h8h8h9', '2025-09-24 14:44:00', 'no'),
(20, 'Operaciones', '103A', 'Hassiel', 'Pendiente', 'ionihpiqewd', '2025-09-25 12:14:00', 'Sí'),
(21, 'Produccion', '304A', 'Pedro', 'Pendiente', '´piqnefpiqefpifqe', '0000-00-00 00:00:00', 'No'),
(22, 'Moldes', '219A', 'Yohnny', 'Pendiente', 'iehihedi', '2025-09-26 08:23:00', 'Sí'),
(23, 'Operaciones', '204B', 'Roberto', 'Pendiente', 'pnpinpi', '2025-09-26 10:29:00', 'No');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `turno` varchar(10) NOT NULL,
  `no_orden` int(11) NOT NULL,
  `Recepcion` datetime NOT NULL DEFAULT current_timestamp(),
  `recep_maquina` datetime NOT NULL DEFAULT current_timestamp(),
  `Tipo` varchar(55) NOT NULL,
  `Hr_ini` time DEFAULT NULL,
  `Hr_Fin` time DEFAULT NULL,
  `t_extra` time DEFAULT NULL,
  `t_realizado` varchar(50) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `Estatus` tinytext NOT NULL,
  `time_total` int(11) GENERATED ALWAYS AS ((time_to_sec(`Hr_Fin`) - time_to_sec(`Hr_ini`) + time_to_sec(`t_extra`)) / 60) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `Nombre`, `turno`, `no_orden`, `Recepcion`, `recep_maquina`, `Tipo`, `Hr_ini`, `Hr_Fin`, `t_extra`, `t_realizado`, `observaciones`, `Estatus`) VALUES
(1, 'HERNAN', 'MATUTINO', 2, '2025-08-13 10:50:01', '2025-08-07 10:50:01', 'ELECTRICO', '15:00:00', '17:00:00', '00:10:00', 'Se cambio un foco', '81841215', 'Terminado'),
(2, 'Melanie', 'Vespertino', 1, '2025-08-13 10:00:00', '2025-08-15 11:21:00', 'Mecanico', '11:21:00', '12:42:00', '00:20:00', 'wva', 'rvw', 'terminado'),
(3, 'Mauricio', 'Mixto', 3, '2025-08-14 10:00:00', '2025-08-14 12:12:00', 'Inyección', '12:13:00', NULL, NULL, 'fveqfqfe', '', 'en proceso'),
(4, 'Carlos', 'Vespertino', 4, '0000-00-00 00:00:00', '2025-08-15 12:26:00', 'Mecanico', '13:26:00', NULL, '00:00:00', 'molde de maq 102 se configuro para otra maquina', 'npncwpin', 'en proceso'),
(5, 'Martha', 'Vespertino', 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Manufactura', '15:51:00', '18:51:00', '00:00:00', 'tifiyf', 'ifi', 'pendiente');

--
-- Índices para tablas volcadas
--

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
  ADD KEY `no_orden` (`no_orden`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD CONSTRAINT `tecnicos_ibfk_1` FOREIGN KEY (`no_orden`) REFERENCES `orden_trabajo` (`id_documento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
