-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 02-11-2025 a las 19:39:12
-- Versión del servidor: 8.0.44
-- Versión de PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_WinKnow`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `codigo_adm` int NOT NULL,
  `Cedula` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rolAdmin` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`codigo_adm`, `Cedula`, `rolAdmin`) VALUES
(1, '0', 'Eso es mentira y descontextualizados'),
(13, '93773381', 'ADMIN'),
(17, '22222222', 'secretario'),
(25, '80731788', 'Main developer'),
(26, '99999999', 'admin'),
(27, '57137379', 'Adscrito'),
(28, '29755117', 'Adscripta'),
(29, '33333333', '.'),
(30, '43153054', 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `IdAsignatura` int NOT NULL,
  `nombreAsignatura` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
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
(7, 'Matematicas CTS'),
(8, 'Ingeniería'),
(9, 'Cálculo'),
(10, 'Sistemas Op'),
(11, 'Ciberseguridad'),
(12, 'Redes'),
(13, 'Prueba asign'),
(14, 'Redes'),
(15, 'Electrónica'),
(16, 'Biología'),
(17, 'Lógica'),
(18, 'Software'),
(19, 'Hardware'),
(20, 'Nivelación'),
(21, 'Ciudadanía'),
(22, 'Historia'),
(23, 'Inglés'),
(24, 'Lengua y Comunicación'),
(25, 'Física'),
(26, 'Matemática'),
(27, 'Programación'),
(28, 'Electrónica'),
(29, 'Biología'),
(30, 'Lógica'),
(31, 'Software'),
(32, 'Hardware'),
(33, 'Nivelación'),
(34, 'Ciudadanía'),
(35, 'Historia'),
(36, 'Inglés'),
(37, 'Lengua y Comunicación'),
(38, 'Física'),
(39, 'Matemática'),
(40, 'Programación'),
(41, 'Electrónica'),
(42, 'Biología'),
(43, 'Lógica'),
(44, 'Software'),
(45, 'Hardware'),
(46, 'Nivelación'),
(47, 'Ciudadanía'),
(48, 'Historia'),
(49, 'Inglés'),
(50, 'Lengua y Comunicación'),
(51, 'Física'),
(52, 'Matemática'),
(53, 'Programación'),
(54, 'Electrónica'),
(55, 'Biología'),
(56, 'Lógica'),
(57, 'Software'),
(58, 'Hardware'),
(59, 'Nivelación de inglés'),
(60, 'Ciudadanía'),
(61, 'Historia'),
(62, 'Inglés'),
(63, 'Lengua y Comunicación'),
(64, 'Física'),
(65, 'Matemática'),
(66, 'Programación'),
(67, 'Electrónica'),
(68, 'Biología'),
(69, 'Lógica'),
(70, 'Software'),
(71, 'Hardware'),
(72, 'Nivelación de inglés'),
(73, 'Ciudadanía'),
(74, 'Historia'),
(75, 'Inglés'),
(76, 'Lengua y Comunicación'),
(77, 'Física'),
(78, 'Matemática'),
(79, 'Programación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_curso`
--

CREATE TABLE `asignatura_curso` (
  `IdAsignatura` int NOT NULL,
  `IdCurso` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `IdCurso` int NOT NULL,
  `Cedula` int NOT NULL,
  `Recursos_Pedidos` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Nombre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
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
  `Cedula` int NOT NULL,
  `IdCurso` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `codigo_doc` int NOT NULL,
  `Cedula` int DEFAULT NULL,
  `contrasenia` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`codigo_doc`, `Cedula`, `contrasenia`) VALUES
(1, 0, '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G'),
(6, 5555555, '$2y$10$Q0LKX8P16hwBgD3mvNUkOe3HA76fG9EM.QF9Jmjw6oO68rzW9fERy'),
(14, 65164413, '$2y$10$H9d/KOuVC7wCay5bIRwRq.5pUClCF..CJGSxKNChA.4jGkn8YPli6'),
(16, 59990844, '$2y$10$YxLKJ8vKZN3pHJmK4Hv2oeXqY9LkJ8vKZN3pHJmK4Hv2oeXqY9LkJO'),
(18, 83256953, '$2y$10$YxLKJ8vKZN3pHJmK4Hv2oeXqY9LkJ8vKZN3pHJmK4Hv2oeXqY9LkJO'),
(19, 46798807, '$2y$10$a9ZZ69/9GGgDj/M/lKAbsOtU.yeRMSTyfTPZsIMRFhoeTnYuIYFFK'),
(20, 28047010, '$2y$10$Pyi7TRJcCK6qnUNCMVH4m.pXcOLvkAjlW/UZkCx1TqFk684QCL2ZK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_recurso`
--

CREATE TABLE `docente_recurso` (
  `IdRecurso` int NOT NULL,
  `Cedula` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `Cedula` int NOT NULL,
  `numeroTelefono` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
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
(28047010, '', ''),
(29755117, '', ''),
(30945628, '', ''),
(33333333, '', ''),
(43153054, '', ''),
(46798807, '', ''),
(50072203, '', ''),
(55555555, '', ''),
(57137379, '', ''),
(57738262, '092047886', ''),
(59990844, '099222333', 'martin.silva@itsp.edu.uy'),
(65164413, '', ''),
(80731788, '', ''),
(83256953, '099111222', 'gaston.gomez@itsp.edu.uy'),
(93773381, '099006955', ''),
(97748239, '', ''),
(99999999, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios`
--

CREATE TABLE `espacios` (
  `IdEspacio` int NOT NULL,
  `NumSalon` int DEFAULT NULL,
  `capacidad` int DEFAULT NULL,
  `Tipo_salon` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `espacios`
--

INSERT INTO `espacios` (`IdEspacio`, `NumSalon`, `capacidad`, `Tipo_salon`) VALUES
(1, 1, 30, 'Aula'),
(2, 2, 30, 'Aula'),
(3, 3, 35, 'Aula'),
(4, 1, 40, 'Salon'),
(5, 2, 45, 'Salon'),
(6, 3, 40, 'Salon'),
(7, 4, 50, 'Salon'),
(8, 5, 45, 'Salon'),
(9, 1, 25, 'Taller Mantenimiento'),
(10, 2, 25, 'Taller Mantenimiento'),
(11, 3, 30, 'Taller Electronica'),
(12, 1, 28, 'Laboratorio Quimica'),
(13, 2, 28, 'Laboratorio Fisica'),
(14, 1, 20, 'Salon Prueba'),
(15, 2, 20, 'Salon Prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `Cedula` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`Cedula`) VALUES
(4871419),
(9697517),
(30945628),
(50072203),
(55555555),
(97748239);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_curso`
--

CREATE TABLE `estudiante_curso` (
  `Cedula` int NOT NULL,
  `IdCurso` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `IdGrupo` int NOT NULL,
  `nombreGrupo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `IdCurso` int NOT NULL,
  `anio` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`IdGrupo`, `nombreGrupo`, `IdCurso`, `anio`) VALUES
(1, '3MD', 1, 3),
(4, '1°MC', 1, 1),
(5, '1°MC', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `ID_horario` int NOT NULL,
  `IdGrupo` int NOT NULL,
  `IdAsignatura` int NOT NULL,
  `Cedula` int NOT NULL,
  `DiaSemana` varchar(20) NOT NULL,
  `HoraInicio` int NOT NULL,
  `HoraFin` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(30, 1, 4, 83256953, 'Viernes', 12, 13),
(31, 5, 15, 0, 'Lunes', 7, 8),
(32, 5, 15, 0, 'Lunes', 8, 9),
(33, 5, 23, 0, 'Lunes', 9, 10),
(34, 5, 23, 0, 'Lunes', 10, 11),
(35, 5, 5, 0, 'Lunes', 11, 12),
(36, 5, 5, 0, 'Lunes', 12, 13),
(37, 5, 22, 0, 'Lunes', 13, 14),
(38, 5, 22, 0, 'Lunes', 14, 15),
(39, 5, 2, 0, 'Lunes', 15, 16),
(40, 5, 2, 0, 'Lunes', 16, 17),
(41, 5, 16, 0, 'Martes', 7, 8),
(42, 5, 16, 0, 'Martes', 8, 9),
(43, 5, 17, 0, 'Martes', 9, 10),
(44, 5, 17, 0, 'Martes', 10, 11),
(45, 5, 6, 0, 'Martes', 11, 12),
(46, 5, 6, 0, 'Martes', 12, 13),
(47, 5, 6, 0, 'Martes', 13, 14),
(48, 5, 2, 0, 'Martes', 14, 15),
(49, 5, 2, 0, 'Martes', 15, 16),
(50, 5, 24, 0, 'Miércoles', 7, 8),
(51, 5, 24, 0, 'Miércoles', 8, 9),
(52, 5, 24, 0, 'Miércoles', 9, 10),
(53, 5, 18, 0, 'Miércoles', 10, 11),
(54, 5, 18, 0, 'Miércoles', 11, 12),
(55, 5, 18, 0, 'Miércoles', 12, 13),
(56, 5, 22, 0, 'Miércoles', 13, 14),
(57, 5, 22, 0, 'Miércoles', 14, 15),
(58, 5, 5, 0, 'Miércoles', 15, 16),
(59, 5, 5, 0, 'Miércoles', 16, 17),
(60, 5, 17, 0, 'Jueves', 8, 9),
(61, 5, 17, 0, 'Jueves', 9, 10),
(62, 5, 24, 0, 'Jueves', 10, 11),
(63, 5, 24, 0, 'Jueves', 11, 12),
(64, 5, 59, 0, 'Jueves', 15, 16),
(65, 5, 59, 0, 'Jueves', 16, 17),
(66, 5, 16, 0, 'Viernes', 7, 8),
(67, 5, 24, 0, 'Viernes', 8, 9),
(68, 5, 24, 0, 'Viernes', 9, 10),
(69, 5, 23, 0, 'Viernes', 10, 11),
(70, 5, 23, 0, 'Viernes', 11, 12),
(71, 5, 19, 0, 'Viernes', 12, 13),
(72, 5, 19, 0, 'Viernes', 13, 14),
(73, 5, 19, 0, 'Viernes', 14, 15),
(74, 5, 59, 0, 'Viernes', 15, 16),
(75, 5, 59, 0, 'Viernes', 16, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `IdRecurso` int NOT NULL,
  `nombre_Recurso` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdEspacio` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `IdReserva` int NOT NULL,
  `IdEspacio` int NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_recurso`
--

CREATE TABLE `reserva_recurso` (
  `IdReservaRecurso` int NOT NULL,
  `IdRecurso` int NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secundario`
--

CREATE TABLE `secundario` (
  `IdCurso` int NOT NULL,
  `anio` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terciario`
--

CREATE TABLE `terciario` (
  `IdCurso` int NOT NULL,
  `NumSemestres` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Cedula` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Contrasenia` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Nombre_usr` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Cedula`, `Contrasenia`, `Nombre_usr`) VALUES
('4871419', '$2y$10$21qZYl4neRCdVtEY3Rz5UeIMWn40L0B6lBChT9fVywhyapzzsFrz6', 'Saldivia Agustin'),
('50072203', '$2y$10$KNsqukTCGPna3s5VEgevsOIt.FiUzJVuWFVyRxU2dtcuU0YGmGWdS', 'Agustin Piñeiro'),
('65164413', '', 'gabito fernandez oril'),
('80731788', '$2y$10$/tsFLl/fwfRIQCXhUREENevVPS.Zy5Pvipx25bhxFRF2n55wuQ.Em', 'John Programador'),
('83256953', '$2y$10$YUjwWWjvoj52hepB.AYvkePAbl8gTKKko75L6anX.saUGc4LfqM8G', 'Prof. Gastón Gómez'),
('9697517', '$2y$10$c2tcSz1ADRLIHFSwQzEZVOuFzjpZQgzR1HjNwL9XnBAizh40SDitm', 'Fidel Olivera'),
('97748239', '$2y$10$SIarh8vUpCkafItHoRVSbuTINKyrARHf/AA3Qwt.hfyjEsy9NJWjG', 'Luca Fontana'),
('99999999', '$2y$10$KIY3jQqDVexYDpjC.rRD4OQWeYKsIIRlJCbmj5XQDfkT0xhIj3Evm', 'WinKnow'),
('57137379', '$2y$10$9dMxY5RCHdatwan8OFlIW.2sAb1HlP3oEiX9sJaDUM/KAK48j3ECG', 'Gustavo'),
('29755117', '$2y$10$Cpv0Mt9Jv1VbTf3p0SIoi.ZzaXGAEUROzuYTBG5lppYam3zy6utcW', 'Jrameau'),
('43153054', '$2y$10$9LILuf062XCCDAhNoPBOQe0zu/ascnLtPTX6RAf5B.RReWuaFJzoa', 'Bruno'),
('46798807', '$2y$10$a9ZZ69/9GGgDj/M/lKAbsOtU.yeRMSTyfTPZsIMRFhoeTnYuIYFFK', 'Bruno'),
('55555555', '$2y$10$BEj8KHYOJJg6ph9SVOARmeQH3APKPE3Zd4s0ne.iHGXm9/jgm.VOW', '5555555555555555555555'),
('28047010', '$2y$10$Pyi7TRJcCK6qnUNCMVH4m.pXcOLvkAjlW/UZkCx1TqFk684QCL2ZK', '28047010'),
('57137379', 'hola', 'gustambo');

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
  ADD PRIMARY KEY (`IdGrupo`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`ID_horario`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`IdRecurso`);

--
-- Indices de la tabla `reserva_recurso`
--
ALTER TABLE `reserva_recurso`
  ADD PRIMARY KEY (`IdReservaRecurso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `codigo_adm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `IdAsignatura` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `codigo_doc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `espacios`
--
ALTER TABLE `espacios`
  MODIFY `IdEspacio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `IdGrupo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `ID_horario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `IdRecurso` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva_recurso`
--
ALTER TABLE `reserva_recurso`
  MODIFY `IdReservaRecurso` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
