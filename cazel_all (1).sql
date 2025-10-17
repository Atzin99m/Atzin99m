-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-08-2025 a las 00:02:02
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
(2, 5555, 'Yohnny Godinez', 'HF5636', 'Mantenimiento'),
(3, 6668, 'Jorge Orozco', 'J8963', 'Ingenieria'),
(4, 56, 'Ericka Rangel', 'er57', 'Direccion'),
(5, 58, 'Melanie SanJuan', 'er56', 'Moldes'),
(6, 3040, 'Roberto Olvera', 'RV6865', 'Finanzas'),
(7, 3039, 'Daniel Contreras', 'CD6866', 'Mejora Continua'),
(8, 6050, 'Octavio', 'O648', 'Ventas'),
(9, 149498, 'jose', 'oobadoycd', 'Operaciones');

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
  `autorizado por` bigint(11) DEFAULT NULL,
  `Estatus` varchar(50) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `fh_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `prioridad` varchar(20) NOT NULL,
  `paro` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id_documento`, `departamento`, `maquina`, `seccion`, `solicitante`, `autorizado por`, `Estatus`, `descripcion`, `tipo`, `fh_emision`, `observaciones`, `prioridad`, `paro`) VALUES
(1, 'Ingenieria', '301', 'Nave 3', 'Cesar', 6668, 'Autorizado', 'joijojeo|nionionoj', 'MttO Correctivo', '2025-08-07 12:38:00', 'momome', '0', '1'),
(2, 'Sistemas', 'N/A', 'Sistemas', 'Carlos', 3038, 'Autorizado', 'gawgwwar', 'MttO Correctivo', '2025-07-21 13:47:00', 'rrrrgRGRRg', 'mo', '0'),
(3, 'Ingenieria', 'N/A', '}}8}8}', 'Fernanda', 56, 'Pendiente', 'gigiougiouiyñi', 'MttO Correctivo', '2025-08-07 11:26:00', 'ooubouu', 'urgente', 'si'),
(4, 'Operaciones', '107', 'Moldes', 'Linda', 58, 'Pendiente', 'checar molde', 'MttO Correctivo', '2025-08-11 14:39:00', 'N/A', 'Normal', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `no_empl` bigint(20) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `turno` varchar(10) NOT NULL,
  `no_orden` int(11) NOT NULL,
  `Recepcion` datetime NOT NULL DEFAULT current_timestamp(),
  `recep_maquina` datetime NOT NULL DEFAULT current_timestamp(),
  `Tipo` varchar(55) NOT NULL,
  `Hr_ini` time DEFAULT NULL,
  `Hr_Fin` time DEFAULT NULL,
  `time_area` time DEFAULT NULL,
  `t_extra` time DEFAULT NULL,
  `t_realizado` varchar(50) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `Estatus` tinytext NOT NULL,
  `time_total` int(11) GENERATED ALWAYS AS ((time_to_sec(`Hr_Fin`) - time_to_sec(`Hr_ini`) + time_to_sec(`time_area`) + time_to_sec(`t_extra`)) / 60) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `no_empl`, `Nombre`, `turno`, `no_orden`, `Recepcion`, `recep_maquina`, `Tipo`, `Hr_ini`, `Hr_Fin`, `time_area`, `t_extra`, `t_realizado`, `observaciones`, `Estatus`) VALUES
(1, 88484, 'HERNAN', 'MATUTINO', 2, '2025-08-13 10:50:01', '2025-08-07 10:50:01', 'ELECTRICO', '15:00:00', '17:00:00', '00:00:00', '00:10:00', 'Se cambio un foco', '81841215', 'Terminado'),
(2, 27872, 'Melanie', 'Vespertino', 1, '2025-08-13 10:00:00', '2025-08-15 11:21:00', 'Mecanico', '11:21:00', '12:42:00', '01:21:00', '00:20:00', 'wva', 'rvw', 'terminado'),
(3, 852828, 'Mauricio', 'Mixto', 3, '2025-08-14 10:00:00', '2025-08-14 12:12:00', 'Inyección', '12:13:00', NULL, NULL, NULL, 'fveqfqfe', '', 'en proceso'),
(4, 495449, 'Carlos', 'Vespertino', 4, '0000-00-00 00:00:00', '2025-08-15 12:26:00', 'Mecanico', '13:26:00', NULL, NULL, '00:00:00', 'molde de maq 102 se configuro para otra maquina', 'npncwpin', 'en proceso'),
(5, 84864, 'Martha', 'Vespertino', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Manufactura', '15:51:00', '18:51:00', '03:08:00', '00:00:00', 'tifiyf', 'ifi', 'pendiente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autorizacion`
--
ALTER TABLE `autorizacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`no_empl`) USING BTREE;

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `autorizado por` (`autorizado por`);

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
-- AUTO_INCREMENT de la tabla `autorizacion`
--
ALTER TABLE `autorizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD CONSTRAINT `orden_trabajo_ibfk_1` FOREIGN KEY (`autorizado por`) REFERENCES `autorizacion` (`no_empl`);

--
-- Filtros para la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD CONSTRAINT `tecnicos_ibfk_1` FOREIGN KEY (`no_orden`) REFERENCES `orden_trabajo` (`id_documento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
