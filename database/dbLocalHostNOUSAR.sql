-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2025 a las 17:36:48
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
  `Cedula` int(11) DEFAULT NULL,
  `EsAdmin` tinyint(1) DEFAULT NULL,
  `rolAdmin` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`codigo_adm`, `Cedula`, `EsAdmin`, `rolAdmin`) VALUES
(1, 0, 1, 'secretario');

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
  `Orientacion` varchar(50) DEFAULT NULL
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
  `contrasenia` varchar(255) NOT NULL,
  `AnioInsercion` date DEFAULT NULL,
  `Estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`codigo_doc`, `Cedula`, `contrasenia`, `AnioInsercion`, `Estado`) VALUES
(1, 0, '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G', '2020-01-01', 'Activo');

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
(57255539, '099006955', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios`
--

CREATE TABLE `espacios` (
  `IdEspacio` int(11) NOT NULL,
  `NumEdificio` int(11) DEFAULT NULL,
  `NumSalon` int(11) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `Estado_Espacio` varchar(20) DEFAULT NULL,
  `Tipo_salon` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `espacios`
--

INSERT INTO `espacios` (`IdEspacio`, `NumEdificio`, `NumSalon`, `capacidad`, `Estado_Espacio`, `Tipo_salon`) VALUES
(1, 1, 1, 35, NULL, 'Aula'),
(2, 1, 2, 40, NULL, 'Aula'),
(3, 1, 3, 30, NULL, 'Aula'),
(4, 0, 10, 25, NULL, 'Taller'),
(5, 2, 20, 30, NULL, 'Taller'),
(6, 2, 21, 25, NULL, 'Taller');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `Cedula` int(11) NOT NULL,
  `FechaNac` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`Cedula`, `FechaNac`) VALUES
(57255539, '2008-04-25');

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
  `Cedula` int(11) NOT NULL,
  `Contrasenia` varchar(255) NOT NULL,
  `Nombre_usr` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Cedula`, `Contrasenia`, `Nombre_usr`) VALUES
(0, '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G', ''),
(57255539, '$2y$10$5CZJK12EjqBAxYcwxNidW.yHIlgAIYxG/36P/yfXagMg2Czjdxapu', 'Santi Rameau');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`codigo_adm`),
  ADD UNIQUE KEY `Cedula` (`Cedula`);

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
  MODIFY `codigo_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `codigo_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `espacios`
--
ALTER TABLE `espacios`
  MODIFY `IdEspacio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `usuarios` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asignatura_curso`
--
ALTER TABLE `asignatura_curso`
  ADD CONSTRAINT `asignatura_curso_ibfk_1` FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura` (`IdAsignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asignatura_curso_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dictan`
--
ALTER TABLE `dictan`
  ADD CONSTRAINT `dictan_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dictan_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `docente_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `usuarios` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente_recurso`
--
ALTER TABLE `docente_recurso`
  ADD CONSTRAINT `docente_recurso_ibfk_1` FOREIGN KEY (`IdRecurso`) REFERENCES `recursos` (`IdRecurso`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `docente_recurso_ibfk_2` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `usuarios` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `usuarios` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD CONSTRAINT `estudiante_curso_ibfk_1` FOREIGN KEY (`Cedula`) REFERENCES `estudiante` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_curso_ibfk_2` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario_ibfk_1` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE,
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
