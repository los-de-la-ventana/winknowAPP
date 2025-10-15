  -- phpMyAdmin SQL Dump
  -- version 5.2.1
  -- https://www.phpmyadmin.net/
  --
  -- Servidor: 127.0.0.1
  -- Tiempo de generación: 14-10-2025 a las 14:34:04
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
  (17, '22222222', 'secretario'),
  (25, '80731788', 'Developer');

  -- --------------------------------------------------------

  --
  -- Estructura de tabla para la tabla `asignatura`
  --

  CREATE TABLE `asignatura` (
    `IdAsignatura` int(11) NOT NULL,
    `nombreAsignatura` varchar(50) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Volcado de datos para la tabla `asignatura`
  --

  INSERT INTO `asignatura` (`IdAsignatura`, `nombreAsignatura`) VALUES
  (1, 'Lengua'),
  (2, 'Matemática'),
  (3, 'Literatura'),
  (4, 'Filosofía'),
  (5, 'Física'),
  (6, 'Programación'),
  (7, 'Materia CTS'),
  (8, 'Ingeniería'),
  (9, 'Cálculo'),
  (10, 'Sistemas Op'),
  (11, 'Ciberseguridad');

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

  --
  -- Volcado de datos para la tabla `cursos`
  --

  INSERT INTO `cursos` (`IdCurso`, `Cedula`, `Recursos_Pedidos`, `Nombre`) VALUES
  (1, 83256953, NULL, 'EMT Bilingüe');

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
  (6, 5555555, '$2y$10$Q0LKX8P16hwBgD3mvNUkOe3HA76fG9EM.QF9Jmjw6oO68rzW9fERy'),
  (14, 65164413, '$2y$10$H9d/KOuVC7wCay5bIRwRq.5pUClCF..CJGSxKNChA.4jGkn8YPli6'),
  (16, 59990844, '$2y$10$YxLKJ8vKZN3pHJmK4Hv2oeXqY9LkJ8vKZN3pHJmK4Hv2oeXqY9LkJO'),
  (18, 83256953, '$2y$10$YxLKJ8vKZN3pHJmK4Hv2oeXqY9LkJ8vKZN3pHJmK4Hv2oeXqY9LkJO');

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
  (4871419, '', ''),
  (5555555, '099006958', ''),
  (9697517, '', ''),
  (22222222, '099006955', ''),
  (30945628, '', ''),
  (50072203, '', ''),
  (57738262, '092047886', ''),
  (59990844, '099222333', 'martin.silva@itsp.edu.uy'),
  (65164413, '', ''),
  (80731788, '', ''),
  (83256953, '099111222', 'gaston.gomez@itsp.edu.uy'),
  (93773381, '099006955', ''),
  (97748239, '', '');

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
  (19, 56, 199, 'Taller'),
  (20, 44, 27, 'Salon');

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
  (4871419),
  (9697517),
  (30945628),
  (50072203),
  (97748239);

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
  -- Estructura de tabla para la tabla `grupo`
  --

  CREATE TABLE `grupo` (
    `IdGrupo` int(11) NOT NULL,
    `nombreGrupo` varchar(50) NOT NULL,
    `IdCurso` int(11) NOT NULL,
    `anio` int(11) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Volcado de datos para la tabla `grupo`
  --

  INSERT INTO `grupo` (`IdGrupo`, `nombreGrupo`, `IdCurso`, `anio`) VALUES
  (1, '3MD', 1, 3);

  -- --------------------------------------------------------

  --
  -- Estructura de tabla para la tabla `horario`
  --

  CREATE TABLE `horario` (
    `ID_horario` int(11) NOT NULL,
    `IdGrupo` int(11) NOT NULL,
    `IdAsignatura` int(11) NOT NULL,
    `Cedula` int(11) NOT NULL,
    `DiaSemana` varchar(20) NOT NULL,
    `HoraInicio` int(11) NOT NULL,
    `HoraFin` int(11) NOT NULL
  ) ;

  --
  -- Volcado de datos para la tabla `horario`
  --

  INSERT INTO `horario` (`ID_horario`, `IdGrupo`, `IdAsignatura`, `Cedula`, `DiaSemana`, `HoraInicio`, `HoraFin`) VALUES
  (1, 1, 1, 83256953, 'Lunes', 7, 8),
  (2, 1, 1, 83256953, 'Lunes', 8, 9),
  (3, 1, 2, 83256953, 'Lunes', 9, 10),
  (4, 1, 3, 83256953, 'Lunes', 10, 11),
  (5, 1, 4, 83256953, 'Lunes', 11, 12),
  (6, 1, 5, 83256953, 'Lunes', 12, 13),
  (7, 1, 6, 83256953, 'Martes', 7, 8),
  (8, 1, 6, 83256953, 'Martes', 8, 9),
  (9, 1, 7, 83256953, 'Martes', 9, 10),
  (10, 1, 7, 83256953, 'Martes', 10, 11),
  (11, 1, 8, 83256953, 'Martes', 11, 12),
  (12, 1, 8, 83256953, 'Martes', 12, 13),
  (13, 1, 9, 83256953, 'Miércoles', 7, 8),
  (14, 1, 9, 83256953, 'Miércoles', 8, 9),
  (15, 1, 7, 83256953, 'Miércoles', 9, 10),
  (16, 1, 7, 83256953, 'Miércoles', 10, 11),
  (17, 1, 5, 83256953, 'Miércoles', 11, 12),
  (18, 1, 5, 83256953, 'Miércoles', 12, 13),
  (19, 1, 10, 83256953, 'Jueves', 7, 8),
  (20, 1, 10, 83256953, 'Jueves', 8, 9),
  (21, 1, 11, 83256953, 'Jueves', 9, 10),
  (22, 1, 11, 83256953, 'Jueves', 10, 11),
  (23, 1, 10, 83256953, 'Jueves', 11, 12),
  (24, 1, 10, 83256953, 'Jueves', 12, 13),
  (25, 1, 2, 83256953, 'Viernes', 7, 8),
  (26, 1, 2, 83256953, 'Viernes', 8, 9),
  (27, 1, 1, 83256953, 'Viernes', 9, 10),
  (28, 1, 1, 83256953, 'Viernes', 10, 11),
  (29, 1, 3, 83256953, 'Viernes', 11, 12),
  (30, 1, 4, 83256953, 'Viernes', 12, 13);

  -- --------------------------------------------------------

  --
  -- Estructura de tabla para la tabla `recursos`
  --

  CREATE TABLE `recursos` (
    `IdRecurso` int(11) NOT NULL,
    `nombre_Recurso` varchar(120) DEFAULT NULL,
    `IdEspacio` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Volcado de datos para la tabla `recursos`
  --

  INSERT INTO `recursos` (`IdRecurso`, `nombre_Recurso`, `IdEspacio`) VALUES
  (1, 'tele', 2),
  (2, 'proyector', 6),
  (3, 'computadora', 6),
  (4, 'HDMI cable', 7),
  (5, 'Mesa', 2),
  (6, 'Control aire acondicionado', 3);

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
  (2, 5, '2025-11-01', 20, 1),
  (3, 6, '2025-10-18', 13, 1),
  (6, 4, '2025-10-26', 9, 1),
  (7, 5, '2025-10-24', 15, 1),
  (9, 20, '2025-10-15', 13, 1);

  -- --------------------------------------------------------

  --
  -- Estructura de tabla para la tabla `reserva_recurso`
  --

  CREATE TABLE `reserva_recurso` (
    `IdReservaRecurso` int(11) NOT NULL,
    `IdRecurso` int(11) NOT NULL,
    `Fecha` date DEFAULT NULL,
    `Hora_Reserva` int(11) DEFAULT NULL,
    `aprobada` tinyint(1) NOT NULL DEFAULT 0
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Volcado de datos para la tabla `reserva_recurso`
  --

  INSERT INTO `reserva_recurso` (`IdReservaRecurso`, `IdRecurso`, `Fecha`, `Hora_Reserva`, `aprobada`) VALUES
  (1, 1, '2025-10-12', 8, 1),
  (5, 2, '2025-10-14', 7, 0),
  (7, 6, '2025-10-17', 13, 0);

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
  ('4871419', '$2y$10$21qZYl4neRCdVtEY3Rz5UeIMWn40L0B6lBChT9fVywhyapzzsFrz6', 'Saldivia Agustin'),
  ('50072203', '$2y$10$KNsqukTCGPna3s5VEgevsOIt.FiUzJVuWFVyRxU2dtcuU0YGmGWdS', 'Agustin Piñeiro'),
  ('65164413', '$2y$10$H9d/KOuVC7wCay5bIRwRq.5pUClCF..CJGSxKNChA.4jGkn8YPli6', 'Profe Ingenieria'),
  ('80731788', '$2y$10$/tsFLl/fwfRIQCXhUREENevVPS.Zy5Pvipx25bhxFRF2n55wuQ.Em', 'John Programador'),
  ('83256953', '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G', 'Prof. Gastón Gómez'),
  ('9697517', '$2y$10$c2tcSz1ADRLIHFSwQzEZVOuFzjpZQgzR1HjNwL9XnBAizh40SDitm', 'Fidel Olivera'),
  ('97748239', '$2y$10$SIarh8vUpCkafItHoRVSbuTINKyrARHf/AA3Qwt.hfyjEsy9NJWjG', 'Luca Fontana');

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
  -- Indices de la tabla `grupo`
  --
  ALTER TABLE `grupo`
    ADD PRIMARY KEY (`IdGrupo`),
    ADD KEY `IdCurso` (`IdCurso`);

  --
  -- Indices de la tabla `horario`
  --
  ALTER TABLE `horario`
    ADD PRIMARY KEY (`ID_horario`),
    ADD KEY `IdGrupo` (`IdGrupo`),
    ADD KEY `IdAsignatura` (`IdAsignatura`),
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
  -- Indices de la tabla `reserva_recurso`
  --
  ALTER TABLE `reserva_recurso`
    ADD PRIMARY KEY (`IdReservaRecurso`),
    ADD KEY `IdRecurso` (`IdRecurso`);

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
    MODIFY `codigo_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

  --
  -- AUTO_INCREMENT de la tabla `asignatura`
  --
  ALTER TABLE `asignatura`
    MODIFY `IdAsignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

  --
  -- AUTO_INCREMENT de la tabla `cursos`
  --
  ALTER TABLE `cursos`
    MODIFY `IdCurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

  --
  -- AUTO_INCREMENT de la tabla `docente`
  --
  ALTER TABLE `docente`
    MODIFY `codigo_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

  --
  -- AUTO_INCREMENT de la tabla `espacios`
  --
  ALTER TABLE `espacios`
    MODIFY `IdEspacio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

  --
  -- AUTO_INCREMENT de la tabla `grupo`
  --
  ALTER TABLE `grupo`
    MODIFY `IdGrupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

  --
  -- AUTO_INCREMENT de la tabla `horario`
  --
  ALTER TABLE `horario`
    MODIFY `ID_horario` int(11) NOT NULL AUTO_INCREMENT;

  --
  -- AUTO_INCREMENT de la tabla `recursos`
  --
  ALTER TABLE `recursos`
    MODIFY `IdRecurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

  --
  -- AUTO_INCREMENT de la tabla `reserva`
  --
  ALTER TABLE `reserva`
    MODIFY `IdReserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

  --
  -- AUTO_INCREMENT de la tabla `reserva_recurso`
  --
  ALTER TABLE `reserva_recurso`
    MODIFY `IdReservaRecurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  -- Filtros para la tabla `grupo`
  --
  ALTER TABLE `grupo`
    ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE;

  --
  -- Filtros para la tabla `horario`
  --
  ALTER TABLE `horario`
    ADD CONSTRAINT `horario_ibfk_1` FOREIGN KEY (`IdGrupo`) REFERENCES `grupo` (`IdGrupo`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `horario_ibfk_2` FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura` (`IdAsignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `horario_ibfk_3` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  -- Filtros para la tabla `reserva_recurso`
  --
  ALTER TABLE `reserva_recurso`
    ADD CONSTRAINT `reserva_recurso_ibfk_1` FOREIGN KEY (`IdRecurso`) REFERENCES `recursos` (`IdRecurso`) ON DELETE CASCADE ON UPDATE CASCADE;

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
