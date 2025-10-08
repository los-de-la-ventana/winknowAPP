-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-10-2025 a las 23:02:56
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
-- Base de datos: `winknow`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `count_all_tables` (IN `db_name` VARCHAR(100))   BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE tbl_name VARCHAR(100);
  DECLARE cur CURSOR FOR 
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = db_name;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur;
  read_loop: LOOP
    FETCH cur INTO tbl_name;
    IF done THEN
      LEAVE read_loop;
    END IF;
    SET @s = CONCAT('SELECT "', tbl_name, '" AS TableName, COUNT(*) AS RowCount FROM ', db_name, '.', tbl_name, ';');
    PREPARE stmt FROM @s;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END LOOP;
  CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `codigo_adm` int(11) NOT NULL,
  `Cedula` varchar(12) DEFAULT NULL,
  `rolAdmin` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`codigo_adm`, `Cedula`, `rolAdmin`) VALUES
(1, '0', 'secretario'),
(13, '93773381', 'ADMIN'),
(14, '80731788', 'PROGRAMADOR'),
(15, '55555555', 'admin'),
(16, '19809833', 'ADMIN TOTAL'),
(17, '22222222', 'secretario'),
(18, '57012127', '1'),
(19, '24854988', 'secretario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `IdAsignatura` int(11) NOT NULL,
  `nombreAsignatura` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_curso`
--

CREATE TABLE `asignatura_curso` (
  `IdAsignatura` int(11) NOT NULL,
  `IdCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `IdCurso` int(11) NOT NULL,
  `Cedula` int(11) NOT NULL,
  `Recursos_Pedidos` varchar(100) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dictan`
--

CREATE TABLE `dictan` (
  `Cedula` int(11) NOT NULL,
  `IdCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `codigo_doc` int(11) NOT NULL,
  `Cedula` int(11) DEFAULT NULL,
  `contrasenia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`codigo_doc`, `Cedula`, `contrasenia`) VALUES
(1, 0, '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G'),
(5, 83256953, '$2y$10$Ga24QGcBx8RdEQR5bpZSFOhxk9bV.G2fNZz4oBSvURkFetUqgunfe'),
(6, 5555555, '$2y$10$Q0LKX8P16hwBgD3mvNUkOe3HA76fG9EM.QF9Jmjw6oO68rzW9fERy'),
(7, 41741768, '$2y$10$uCtcBBB..zi55lpZ/g3LVeV686KRMWA/EfxsOcZ6zNoqH2qVu7C52'),
(8, 59990844, '$2y$10$v0iweMKV/Ru09J.Xk.b5JO2n8y5MEq46K8nF7N0lvkMfVkwyCWBcO'),
(9, 98486040, '$2y$10$cieuPtrEtHyM6wSN2flmp.b9mxMSgnrukL.DLOKInNttw/jZVIBEm'),
(10, 34843901, '$2y$10$qvJGnxftbqMj.T0Wwrm56OjhxFEQospfTNe7f5a4/DcOFt3Rk6gBK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_recurso`
--

CREATE TABLE `docente_recurso` (
  `IdRecurso` int(11) NOT NULL,
  `Cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `Cedula` int(11) NOT NULL,
  `numeroTelefono` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `email`
--

INSERT INTO `email` (`Cedula`, `numeroTelefono`, `email`) VALUES
(0, '', ''),
(5555555, '099006958', ''),
(19809833, '099006955', ''),
(22222222, '099006955', ''),
(24854988, '099006955', ''),
(30945628, '', ''),
(34843901, '', ''),
(39295446, '099006955', ''),
(41741768, '', ''),
(55555555, '099006955', ''),
(57012127, '099006958', 'santirameau@gmail.com'),
(57255539, '099006955', ''),
(57738262, '092047886', ''),
(59408918, '', ''),
(59990844, '', ''),
(78124763, '', ''),
(80731788, '099006958', ''),
(83256953, '099006958', ''),
(93773381, '099006955', ''),
(98486040, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios`
--

CREATE TABLE `espacios` (
  `IdEspacio` int(11) NOT NULL,
  `NumSalon` int(11) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `Tipo_salon` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `espacios`
--

INSERT INTO `espacios` (`IdEspacio`, `NumSalon`, `capacidad`, `Tipo_salon`) VALUES
(2, 102, 40, 'Aula'),
(3, 103, 30, 'Aula'),
(4, 201, 25, 'Taller'),
(5, 202, 28, 'Taller'),
(6, 203, 22, 'Taller'),
(7, 301, 30, 'Laboratorio'),
(9, 402, 50, 'Salon'),
(10, 403, 40, 'Salon'),
(14, 222, 1, 'Salon'),
(17, 666, 200, 'Laboratorio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `Cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`Cedula`) VALUES
(30945628),
(39295446),
(57255539),
(59408918),
(78124763);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_curso`
--

CREATE TABLE `estudiante_curso` (
  `Cedula` int(11) NOT NULL,
  `IdCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `ID_horario` int(11) NOT NULL,
  `Hora` time DEFAULT NULL,
  `Dia` date DEFAULT NULL,
  `IdCurso` int(11) NOT NULL,
  `Cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `IdRecurso` int(11) NOT NULL,
  `nombre_Recurso` varchar(120) DEFAULT NULL,
  `IdEspacio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `IdReserva` int(11) NOT NULL,
  `IdEspacio` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secundario`
--

CREATE TABLE `secundario` (
  `IdCurso` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terciario`
--

CREATE TABLE `terciario` (
  `IdCurso` int(11) NOT NULL,
  `NumSemestres` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Cedula` varchar(12) NOT NULL,
  `Contrasenia` varchar(255) NOT NULL,
  `Nombre_usr` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Cedula`, `Contrasenia`, `Nombre_usr`) VALUES
('19809833', '$2y$10$ZjmCaK9icbz38Dnw8flxZOW1mYD.MPI5ylb9D2ZJVMunV5dwOp1.2', 'Qatest'),
('24854988', '$2y$10$HAnzJ.uunxJliAIgeJwed.AqNaSw/G2P8gH2A8QYfCgJbOuApbCsq', 'pruebaregistro'),
('39295446', '$2y$10$UKKFI3D3qxUTPJEwLaA.aupXiUjWHRYDVnQR56okV5gf8vggl5kqa', 'Fede'),
('41741768', '$2y$10$uCtcBBB..zi55lpZ/g3LVeV686KRMWA/EfxsOcZ6zNoqH2qVu7C52', 'wwww'),
('55555555', '$2y$10$hxRsmkN11e2A/UeLhrobceBYi6WH/zCbtxUpV8BLHvWAPA5RW11j2', 'errordeprueba'),
('57012127', '$2y$10$d4vhaRlTwd44mfrvM4C5ae//h9Im1lGTPr3HCd2Z2f91sK281VWRG', 'ed'),
('57255539', '$2y$10$5CZJK12EjqBAxYcwxNidW.yHIlgAIYxG/36P/yfXagMg2Czjdxapu', 'Santi Rameau'),
('59408918', '$2y$10$T3JSO8mU.24NVITW9YyMn.P/fzU.2VzEtNs9xbZ/9q7UVKk0B.qfK', 'asdadas'),
('59990844', '$2y$10$v0iweMKV/Ru09J.Xk.b5JO2n8y5MEq46K8nF7N0lvkMfVkwyCWBcO', 'assaas'),
('78124763', '$2y$10$fFuFCgBjVH3d3.i4pLq2lO0nLI0cHQGw1euzl4V2T4JjNP4DSGpg6', 'gsdfg'),
('80731788', '$2y$10$Koc..wS9Ko2bnCtukIPGw.Odrh3wMDk1Z9K7z5KPo2GHhK4eXkUYi', 'Bianchi Matias'),
('83256953', '$2y$10$Ga24QGcBx8RdEQR5bpZSFOhxk9bV.G2fNZz4oBSvURkFetUqgunfe', 'Gaston Gomez'),
('98486040', '$2y$10$cieuPtrEtHyM6wSN2flmp.b9mxMSgnrukL.DLOKInNttw/jZVIBEm', 'asdfas');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`codigo_adm`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`IdAsignatura`);

--
-- Indices de la tabla `asignatura_curso`
--
ALTER TABLE `asignatura_curso`
  ADD PRIMARY KEY (`IdAsignatura`,`IdCurso`),
  ADD KEY `IdCurso` (`IdCurso`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`IdCurso`),
  ADD KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `dictan`
--
ALTER TABLE `dictan`
  ADD PRIMARY KEY (`Cedula`,`IdCurso`),
  ADD KEY `IdCurso` (`IdCurso`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`codigo_doc`),
  ADD UNIQUE KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `docente_recurso`
--
ALTER TABLE `docente_recurso`
  ADD PRIMARY KEY (`IdRecurso`,`Cedula`),
  ADD KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`Cedula`,`email`);

--
-- Indices de la tabla `espacios`
--
ALTER TABLE `espacios`
  ADD PRIMARY KEY (`IdEspacio`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`Cedula`);

--
-- Indices de la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD PRIMARY KEY (`Cedula`,`IdCurso`),
  ADD KEY `IdCurso` (`IdCurso`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`ID_horario`),
  ADD KEY `IdCurso` (`IdCurso`),
  ADD KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`IdRecurso`),
  ADD KEY `IdEspacio` (`IdEspacio`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`IdReserva`),
  ADD KEY `IdEspacio` (`IdEspacio`);

--
-- Indices de la tabla `secundario`
--
ALTER TABLE `secundario`
  ADD PRIMARY KEY (`IdCurso`);

--
-- Indices de la tabla `terciario`
--
ALTER TABLE `terciario`
  ADD PRIMARY KEY (`IdCurso`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `codigo_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `IdAsignatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `IdCurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `codigo_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `espacios`
--
ALTER TABLE `espacios`
  MODIFY `IdEspacio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `ID_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `IdRecurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `IdReserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignatura_curso`
--
ALTER TABLE `asignatura_curso`
  ADD CONSTRAINT `asignatura_curso_ibfk_1` FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura` (`IdAsignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asignatura_curso_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dictan`
--
ALTER TABLE `dictan`
  ADD CONSTRAINT `dictan_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente_recurso`
--
ALTER TABLE `docente_recurso`-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-10-2025 a las 17:10:02
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
-- Base de datos: `db_winknow`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `count_all_tables` (IN `db_name` VARCHAR(100))   BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE tbl_name VARCHAR(100);
  DECLARE cur CURSOR FOR 
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = db_name;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur;
  read_loop: LOOP
    FETCH cur INTO tbl_name;
    IF done THEN
      LEAVE read_loop;
    END IF;
    SET @s = CONCAT('SELECT "', tbl_name, '" AS TableName, COUNT(*) AS RowCount FROM ', db_name, '.', tbl_name, ';');
    PREPARE stmt FROM @s;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END LOOP;
  CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `codigo_adm` int(11) NOT NULL,
  `Cedula` varchar(12) DEFAULT NULL,
  `rolAdmin` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`codigo_adm`, `Cedula`, `rolAdmin`) VALUES
(1, '0', 'secretario'),
(13, '93773381', 'ADMIN'),
(14, '80731788', 'PROGRAMADOR'),
(16, '19809833', 'ADMIN TOTAL'),
(17, '22222222', 'secretario'),
(19, '24854988', 'secretario'),
(20, '15396759', 'adscripta'),
(21, '10883276', 'programmer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `IdAsignatura` int(11) NOT NULL,
  `nombreAsignatura` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_curso`
--

CREATE TABLE `asignatura_curso` (
  `IdAsignatura` int(11) NOT NULL,
  `IdCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `IdCurso` int(11) NOT NULL,
  `Cedula` int(11) NOT NULL,
  `Recursos_Pedidos` varchar(100) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dictan`
--

CREATE TABLE `dictan` (
  `Cedula` int(11) NOT NULL,
  `IdCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `codigo_doc` int(11) NOT NULL,
  `Cedula` int(11) DEFAULT NULL,
  `contrasenia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`codigo_doc`, `Cedula`, `contrasenia`) VALUES
(1, 0, '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G'),
(5, 83256953, '$2y$10$Ga24QGcBx8RdEQR5bpZSFOhxk9bV.G2fNZz4oBSvURkFetUqgunfe'),
(6, 5555555, '$2y$10$Q0LKX8P16hwBgD3mvNUkOe3HA76fG9EM.QF9Jmjw6oO68rzW9fERy'),
(8, 59990844, '$2y$10$v0iweMKV/Ru09J.Xk.b5JO2n8y5MEq46K8nF7N0lvkMfVkwyCWBcO'),
(10, 34843901, '$2y$10$qvJGnxftbqMj.T0Wwrm56OjhxFEQospfTNe7f5a4/DcOFt3Rk6gBK'),
(11, 49055896, '$2y$10$B5gZRDA3IV/bHKAKUpa1pe8ODzDQ2LNI3LlECJX209VMV0QBdznFq'),
(13, 43941281, '$2y$10$lDbw7bwtJRAf58W.7MHlmezDx7kIJSG5xWuYzk29les8FN4b1z1Gq');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_recurso`
--

CREATE TABLE `docente_recurso` (
  `IdRecurso` int(11) NOT NULL,
  `Cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `Cedula` int(11) NOT NULL,
  `numeroTelefono` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `email`
--

INSERT INTO `email` (`Cedula`, `numeroTelefono`, `email`) VALUES
(0, '', ''),
(5555555, '099006958', ''),
(10883276, '', ''),
(15396759, '', ''),
(19809833, '099006955', ''),
(22222222, '099006955', ''),
(24854988, '099006955', ''),
(30945628, '', ''),
(34843901, '', ''),
(39295446, '099006955', ''),
(43941281, '', ''),
(49055896, '', ''),
(57255539, '099006955', ''),
(57738262, '092047886', ''),
(59990844, '', ''),
(80731788, '099006958', ''),
(83256953, '099006958', ''),
(93773381, '099006955', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios`
--

CREATE TABLE `espacios` (
  `IdEspacio` int(11) NOT NULL,
  `NumSalon` int(11) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `Tipo_salon` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `espacios`
--

INSERT INTO `espacios` (`IdEspacio`, `NumSalon`, `capacidad`, `Tipo_salon`) VALUES
(2, 102, 40, 'Aula'),
(3, 103, 30, 'Aula'),
(4, 201, 25, 'Taller'),
(5, 202, 28, 'Taller'),
(6, 203, 22, 'Taller'),
(7, 301, 30, 'Laboratorio'),
(9, 402, 50, 'Salon'),
(10, 403, 40, 'Salon'),
(14, 222, 1, 'Salon'),
(17, 666, 200, 'Laboratorio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `Cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`Cedula`) VALUES
(30945628),
(39295446),
(57255539);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_curso`
--

CREATE TABLE `estudiante_curso` (
  `Cedula` int(11) NOT NULL,
  `IdCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `ID_horario` int(11) NOT NULL,
  `Hora` time DEFAULT NULL,
  `Dia` date DEFAULT NULL,
  `IdCurso` int(11) NOT NULL,
  `Cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `IdRecurso` int(11) NOT NULL,
  `nombre_Recurso` varchar(120) DEFAULT NULL,
  `IdEspacio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `IdReserva` int(11) NOT NULL,
  `IdEspacio` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int(11) DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`IdReserva`, `IdEspacio`, `Fecha`, `Hora_Reserva`, `aprobada`) VALUES
(1, 6, '2025-10-07', 7, 0),
(2, 5, '2025-11-01', 20, 0),
(3, 6, '2025-10-18', 13, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secundario`
--

CREATE TABLE `secundario` (
  `IdCurso` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terciario`
--

CREATE TABLE `terciario` (
  `IdCurso` int(11) NOT NULL,
  `NumSemestres` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Cedula` varchar(12) NOT NULL,
  `Contrasenia` varchar(255) NOT NULL,
  `Nombre_usr` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Cedula`, `Contrasenia`, `Nombre_usr`) VALUES
('10883276', '$2y$10$Xkdi4K6ZvR8jh8sXDMS4.OL.4RJ6hBrJc4atBI2Ji272iV0kl9hlq', 'Lauta Almiron'),
('15396759', '$2y$10$cdcSaV6wR7XtmXXsR93Bru9V1I1N1N1U.edjJpUB36QWNjtDncdCC', 'Prueba2'),
('19809833', '$2y$10$ZjmCaK9icbz38Dnw8flxZOW1mYD.MPI5ylb9D2ZJVMunV5dwOp1.2', 'Qatest'),
('24854988', '$2y$10$HAnzJ.uunxJliAIgeJwed.AqNaSw/G2P8gH2A8QYfCgJbOuApbCsq', 'pruebaregistro'),
('39295446', '$2y$10$UKKFI3D3qxUTPJEwLaA.aupXiUjWHRYDVnQR56okV5gf8vggl5kqa', 'Fede'),
('43941281', '$2y$10$lDbw7bwtJRAf58W.7MHlmezDx7kIJSG5xWuYzk29les8FN4b1z1Gq', 'docenteBorrar'),
('49055896', '$2y$10$B5gZRDA3IV/bHKAKUpa1pe8ODzDQ2LNI3LlECJX209VMV0QBdznFq', 'Federico'),
('57255539', '$2y$10$5CZJK12EjqBAxYcwxNidW.yHIlgAIYxG/36P/yfXagMg2Czjdxapu', 'Santi Rameau'),
('59990844', '$2y$10$v0iweMKV/Ru09J.Xk.b5JO2n8y5MEq46K8nF7N0lvkMfVkwyCWBcO', 'assaas'),
('80731788', '$2y$10$Koc..wS9Ko2bnCtukIPGw.Odrh3wMDk1Z9K7z5KPo2GHhK4eXkUYi', 'Bianchi Matias'),
('83256953', '$2y$10$Ga24QGcBx8RdEQR5bpZSFOhxk9bV.G2fNZz4oBSvURkFetUqgunfe', 'Gaston Gomez');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`codigo_adm`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`IdAsignatura`);

--
-- Indices de la tabla `asignatura_curso`
--
ALTER TABLE `asignatura_curso`
  ADD PRIMARY KEY (`IdAsignatura`,`IdCurso`),
  ADD KEY `IdCurso` (`IdCurso`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`IdCurso`),
  ADD KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `dictan`
--
ALTER TABLE `dictan`
  ADD PRIMARY KEY (`Cedula`,`IdCurso`),
  ADD KEY `IdCurso` (`IdCurso`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`codigo_doc`),
  ADD UNIQUE KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `docente_recurso`
--
ALTER TABLE `docente_recurso`
  ADD PRIMARY KEY (`IdRecurso`,`Cedula`),
  ADD KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`Cedula`,`email`);

--
-- Indices de la tabla `espacios`
--
ALTER TABLE `espacios`
  ADD PRIMARY KEY (`IdEspacio`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`Cedula`);

--
-- Indices de la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD PRIMARY KEY (`Cedula`,`IdCurso`),
  ADD KEY `IdCurso` (`IdCurso`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`ID_horario`),
  ADD KEY `IdCurso` (`IdCurso`),
  ADD KEY `Cedula` (`Cedula`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`IdRecurso`),
  ADD KEY `IdEspacio` (`IdEspacio`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`IdReserva`),
  ADD KEY `IdEspacio` (`IdEspacio`);

--
-- Indices de la tabla `secundario`
--
ALTER TABLE `secundario`
  ADD PRIMARY KEY (`IdCurso`);

--
-- Indices de la tabla `terciario`
--
ALTER TABLE `terciario`
  ADD PRIMARY KEY (`IdCurso`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `codigo_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `IdAsignatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `IdCurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `codigo_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `espacios`
--
ALTER TABLE `espacios`
  MODIFY `IdEspacio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `ID_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `IdRecurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `IdReserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignatura_curso`
--
ALTER TABLE `asignatura_curso`
  ADD CONSTRAINT `asignatura_curso_ibfk_1` FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura` (`IdAsignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asignatura_curso_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dictan`
--
ALTER TABLE `dictan`
  ADD CONSTRAINT `dictan_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente_recurso`
--
ALTER TABLE `docente_recurso`
  ADD CONSTRAINT `docente_recurso_ibfk_2` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD CONSTRAINT `estudiante_curso_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario_ibfk_2` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD CONSTRAINT `recursos_ibfk_1` FOREIGN KEY (`IdEspacio`) REFERENCES `espacios` (`IdEspacio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`IdEspacio`) REFERENCES `espacios` (`IdEspacio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `secundario`
--
ALTER TABLE `secundario`
  ADD CONSTRAINT `secundario_ibfk_1` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `terciario`
--
ALTER TABLE `terciario`
  ADD CONSTRAINT `terciario_ibfk_1` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

  ADD CONSTRAINT `docente_recurso_ibfk_2` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD CONSTRAINT `estudiante_curso_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario_ibfk_2` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD CONSTRAINT `recursos_ibfk_1` FOREIGN KEY (`IdEspacio`) REFERENCES `espacios` (`IdEspacio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`IdEspacio`) REFERENCES `espacios` (`IdEspacio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `secundario`
--
ALTER TABLE `secundario`
  ADD CONSTRAINT `secundario_ibfk_1` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `terciario`
--
ALTER TABLE `terciario`
  ADD CONSTRAINT `terciario_ibfk_1` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
