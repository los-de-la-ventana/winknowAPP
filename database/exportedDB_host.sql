-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-11-2025 a las 19:09:11
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
(13, '93773381', 'ADMIN'),
(25, '80731788', 'Main developer'),
(28, '29755117', 'Adscripta'),
(31, '79538818', 'Adscripta (matutino)');

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
(59, 'Nivelación de inglés');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_backup_20241106`
--

CREATE TABLE `asignatura_backup_20241106` (
  `IdAsignatura` int NOT NULL DEFAULT '0',
  `nombreAsignatura` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `asignatura_backup_20241106`
--

INSERT INTO `asignatura_backup_20241106` (`IdAsignatura`, `nombreAsignatura`) VALUES
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

--
-- Volcado de datos para la tabla `asignatura_curso`
--

INSERT INTO `asignatura_curso` (`IdAsignatura`, `IdCurso`) VALUES
(2, 2),
(9, 2),
(11, 3),
(21, 4);

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
(1, 83256953, NULL, 'EMT Bilingüe'),
(2, 46798807, NULL, 'prueba'),
(4, 83256953, NULL, 'holaprueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dictan`
--

CREATE TABLE `dictan` (
  `Cedula` int NOT NULL,
  `IdCurso` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dictan`
--

INSERT INTO `dictan` (`Cedula`, `IdCurso`) VALUES
(46798807, 2),
(46798807, 3),
(83256953, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `codigo_doc` int NOT NULL,
  `Cedula` int DEFAULT NULL,
  `contrasenia` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
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
(23, 28832784, NULL),
(24, 34247591, NULL),
(25, 36129797, NULL),
(27, 57015969, NULL);

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
(3, 3, 35, 'Aula'),
(4, 1, 40, 'Salon'),
(6, 3, 40, 'Salon'),
(7, 4, 50, 'Salon'),
(8, 5, 45, 'Salon'),
(9, 1, 25, 'Taller Mantenimiento'),
(11, 3, 30, 'Taller Electronica'),
(12, 1, 28, 'Laboratorio Quimica'),
(14, 1, 20, 'Salon Prueba'),
(16, 101, 100, 'Laboratorio'),
(17, 2, 80, 'Taller'),
(19, 33, 33, 'Taller');

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
(52836584),
(55555555),
(84559742),
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
  `HoraInicio` time NOT NULL,
  `HoraFin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`ID_horario`, `IdGrupo`, `IdAsignatura`, `Cedula`, `DiaSemana`, `HoraInicio`, `HoraFin`) VALUES
(1, 1, 1, 83256953, 'Lunes', '07:00:00', '07:45:00'),
(2, 1, 1, 83256953, 'Lunes', '07:50:00', '08:35:00'),
(3, 1, 2, 83256953, 'Lunes', '08:40:00', '09:25:00'),
(4, 1, 3, 83256953, 'Lunes', '09:30:00', '10:15:00'),
(5, 1, 4, 83256953, 'Lunes', '10:20:00', '11:55:00'),
(6, 1, 5, 83256953, 'Lunes', '12:00:00', '12:45:00'),
(7, 1, 6, 83256953, 'Martes', '07:00:00', '07:45:00'),
(8, 1, 6, 83256953, 'Martes', '07:50:00', '08:35:00'),
(9, 1, 7, 83256953, 'Martes', '08:40:00', '09:25:00'),
(10, 1, 7, 83256953, 'Martes', '09:30:00', '10:15:00'),
(11, 1, 8, 83256953, 'Martes', '10:20:00', '11:55:00'),
(12, 1, 8, 83256953, 'Martes', '12:00:00', '12:45:00'),
(13, 1, 9, 83256953, 'Miércoles', '07:00:00', '07:45:00'),
(14, 1, 9, 83256953, 'Miércoles', '07:50:00', '08:35:00'),
(15, 1, 7, 83256953, 'Miércoles', '08:40:00', '09:25:00'),
(16, 1, 7, 83256953, 'Miércoles', '09:30:00', '10:15:00'),
(17, 1, 5, 83256953, 'Miércoles', '10:20:00', '11:55:00'),
(18, 1, 5, 83256953, 'Miércoles', '12:00:00', '12:45:00'),
(19, 1, 10, 83256953, 'Jueves', '07:00:00', '07:45:00'),
(20, 1, 10, 83256953, 'Jueves', '07:50:00', '08:35:00'),
(21, 1, 11, 83256953, 'Jueves', '08:40:00', '09:25:00'),
(22, 1, 11, 83256953, 'Jueves', '09:30:00', '10:15:00'),
(23, 1, 10, 83256953, 'Jueves', '10:20:00', '11:55:00'),
(24, 1, 10, 83256953, 'Jueves', '12:00:00', '12:45:00'),
(25, 1, 2, 83256953, 'Viernes', '07:00:00', '07:45:00'),
(26, 1, 2, 83256953, 'Viernes', '07:50:00', '08:35:00'),
(27, 1, 1, 83256953, 'Viernes', '08:40:00', '09:25:00'),
(28, 1, 1, 83256953, 'Viernes', '09:30:00', '10:15:00'),
(29, 1, 3, 83256953, 'Viernes', '10:20:00', '11:55:00'),
(30, 1, 4, 83256953, 'Viernes', '12:00:00', '12:45:00'),
(31, 5, 15, 0, 'Lunes', '07:00:00', '07:45:00'),
(32, 5, 15, 0, 'Lunes', '07:50:00', '08:35:00'),
(33, 5, 23, 0, 'Lunes', '08:40:00', '09:25:00'),
(34, 5, 23, 0, 'Lunes', '09:30:00', '10:15:00'),
(35, 5, 5, 0, 'Lunes', '10:20:00', '11:55:00'),
(36, 5, 5, 0, 'Lunes', '12:00:00', '12:45:00'),
(37, 5, 22, 0, 'Lunes', '12:50:00', '13:35:00'),
(38, 5, 22, 0, 'Lunes', '13:40:00', '14:25:00'),
(39, 5, 2, 0, 'Lunes', '14:30:00', '15:15:00'),
(40, 5, 2, 0, 'Lunes', '15:20:00', '16:05:00'),
(41, 5, 16, 0, 'Martes', '07:00:00', '07:45:00'),
(42, 5, 16, 0, 'Martes', '07:50:00', '08:35:00'),
(43, 5, 17, 0, 'Martes', '08:40:00', '09:25:00'),
(44, 5, 17, 0, 'Martes', '09:30:00', '10:15:00'),
(45, 5, 6, 0, 'Martes', '10:20:00', '11:55:00'),
(46, 5, 6, 0, 'Martes', '12:00:00', '12:45:00'),
(47, 5, 6, 0, 'Martes', '12:50:00', '13:35:00'),
(48, 5, 2, 0, 'Martes', '13:40:00', '14:25:00'),
(49, 5, 2, 0, 'Martes', '14:30:00', '15:15:00'),
(50, 5, 24, 0, 'Miércoles', '07:00:00', '07:45:00'),
(51, 5, 24, 0, 'Miércoles', '07:50:00', '08:35:00'),
(52, 5, 24, 0, 'Miércoles', '08:40:00', '09:25:00'),
(53, 5, 18, 0, 'Miércoles', '09:30:00', '10:15:00'),
(54, 5, 18, 0, 'Miércoles', '10:20:00', '11:55:00'),
(55, 5, 18, 0, 'Miércoles', '12:00:00', '12:45:00'),
(56, 5, 22, 0, 'Miércoles', '12:50:00', '13:35:00'),
(57, 5, 22, 0, 'Miércoles', '13:40:00', '14:25:00'),
(58, 5, 5, 0, 'Miércoles', '14:30:00', '15:15:00'),
(59, 5, 5, 0, 'Miércoles', '15:20:00', '16:05:00'),
(60, 5, 17, 0, 'Jueves', '07:50:00', '08:35:00'),
(61, 5, 17, 0, 'Jueves', '08:40:00', '09:25:00'),
(62, 5, 24, 0, 'Jueves', '09:30:00', '10:15:00'),
(63, 5, 24, 0, 'Jueves', '10:20:00', '11:55:00'),
(64, 5, 59, 0, 'Jueves', '14:30:00', '15:15:00'),
(65, 5, 59, 0, 'Jueves', '15:20:00', '16:05:00'),
(66, 5, 16, 0, 'Viernes', '07:00:00', '07:45:00'),
(67, 5, 24, 0, 'Viernes', '07:50:00', '08:35:00'),
(68, 5, 24, 0, 'Viernes', '08:40:00', '09:25:00'),
(69, 5, 23, 0, 'Viernes', '09:30:00', '10:15:00'),
(70, 5, 23, 0, 'Viernes', '10:20:00', '11:55:00'),
(71, 5, 19, 0, 'Viernes', '12:00:00', '12:45:00'),
(72, 5, 19, 0, 'Viernes', '12:50:00', '13:35:00'),
(73, 5, 19, 0, 'Viernes', '13:40:00', '14:25:00'),
(74, 5, 59, 0, 'Viernes', '14:30:00', '15:15:00'),
(75, 5, 59, 0, 'Viernes', '15:20:00', '16:05:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_backup_20241106`
--

CREATE TABLE `horario_backup_20241106` (
  `ID_horario` int NOT NULL DEFAULT '0',
  `IdGrupo` int NOT NULL,
  `IdAsignatura` int NOT NULL,
  `Cedula` int NOT NULL,
  `DiaSemana` varchar(20) NOT NULL,
  `HoraInicio` time NOT NULL,
  `HoraFin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `horario_backup_20241106`
--

INSERT INTO `horario_backup_20241106` (`ID_horario`, `IdGrupo`, `IdAsignatura`, `Cedula`, `DiaSemana`, `HoraInicio`, `HoraFin`) VALUES
(1, 1, 1, 83256953, 'Lunes', '07:00:00', '07:45:00'),
(2, 1, 1, 83256953, 'Lunes', '07:50:00', '08:35:00'),
(3, 1, 2, 83256953, 'Lunes', '08:40:00', '09:25:00'),
(4, 1, 3, 83256953, 'Lunes', '09:30:00', '10:15:00'),
(5, 1, 4, 83256953, 'Lunes', '10:20:00', '11:55:00'),
(6, 1, 5, 83256953, 'Lunes', '12:00:00', '12:45:00'),
(7, 1, 6, 83256953, 'Martes', '07:00:00', '07:45:00'),
(8, 1, 6, 83256953, 'Martes', '07:50:00', '08:35:00'),
(9, 1, 7, 83256953, 'Martes', '08:40:00', '09:25:00'),
(10, 1, 7, 83256953, 'Martes', '09:30:00', '10:15:00'),
(11, 1, 8, 83256953, 'Martes', '10:20:00', '11:55:00'),
(12, 1, 8, 83256953, 'Martes', '12:00:00', '12:45:00'),
(13, 1, 9, 83256953, 'Miércoles', '07:00:00', '07:45:00'),
(14, 1, 9, 83256953, 'Miércoles', '07:50:00', '08:35:00'),
(15, 1, 7, 83256953, 'Miércoles', '08:40:00', '09:25:00'),
(16, 1, 7, 83256953, 'Miércoles', '09:30:00', '10:15:00'),
(17, 1, 5, 83256953, 'Miércoles', '10:20:00', '11:55:00'),
(18, 1, 5, 83256953, 'Miércoles', '12:00:00', '12:45:00'),
(19, 1, 10, 83256953, 'Jueves', '07:00:00', '07:45:00'),
(20, 1, 10, 83256953, 'Jueves', '07:50:00', '08:35:00'),
(21, 1, 11, 83256953, 'Jueves', '08:40:00', '09:25:00'),
(22, 1, 11, 83256953, 'Jueves', '09:30:00', '10:15:00'),
(23, 1, 10, 83256953, 'Jueves', '10:20:00', '11:55:00'),
(24, 1, 10, 83256953, 'Jueves', '12:00:00', '12:45:00'),
(25, 1, 2, 83256953, 'Viernes', '07:00:00', '07:45:00'),
(26, 1, 2, 83256953, 'Viernes', '07:50:00', '08:35:00'),
(27, 1, 1, 83256953, 'Viernes', '08:40:00', '09:25:00'),
(28, 1, 1, 83256953, 'Viernes', '09:30:00', '10:15:00'),
(29, 1, 3, 83256953, 'Viernes', '10:20:00', '11:55:00'),
(30, 1, 4, 83256953, 'Viernes', '12:00:00', '12:45:00'),
(31, 5, 15, 0, 'Lunes', '07:00:00', '07:45:00'),
(32, 5, 15, 0, 'Lunes', '07:50:00', '08:35:00'),
(33, 5, 23, 0, 'Lunes', '08:40:00', '09:25:00'),
(34, 5, 23, 0, 'Lunes', '09:30:00', '10:15:00'),
(35, 5, 5, 0, 'Lunes', '10:20:00', '11:55:00'),
(36, 5, 5, 0, 'Lunes', '12:00:00', '12:45:00'),
(37, 5, 22, 0, 'Lunes', '12:50:00', '13:35:00'),
(38, 5, 22, 0, 'Lunes', '13:40:00', '14:25:00'),
(39, 5, 2, 0, 'Lunes', '14:30:00', '15:15:00'),
(40, 5, 2, 0, 'Lunes', '15:20:00', '16:05:00'),
(41, 5, 16, 0, 'Martes', '07:00:00', '07:45:00'),
(42, 5, 16, 0, 'Martes', '07:50:00', '08:35:00'),
(43, 5, 17, 0, 'Martes', '08:40:00', '09:25:00'),
(44, 5, 17, 0, 'Martes', '09:30:00', '10:15:00'),
(45, 5, 6, 0, 'Martes', '10:20:00', '11:55:00'),
(46, 5, 6, 0, 'Martes', '12:00:00', '12:45:00'),
(47, 5, 6, 0, 'Martes', '12:50:00', '13:35:00'),
(48, 5, 2, 0, 'Martes', '13:40:00', '14:25:00'),
(49, 5, 2, 0, 'Martes', '14:30:00', '15:15:00'),
(50, 5, 24, 0, 'Miércoles', '07:00:00', '07:45:00'),
(51, 5, 24, 0, 'Miércoles', '07:50:00', '08:35:00'),
(52, 5, 24, 0, 'Miércoles', '08:40:00', '09:25:00'),
(53, 5, 18, 0, 'Miércoles', '09:30:00', '10:15:00'),
(54, 5, 18, 0, 'Miércoles', '10:20:00', '11:55:00'),
(55, 5, 18, 0, 'Miércoles', '12:00:00', '12:45:00'),
(56, 5, 22, 0, 'Miércoles', '12:50:00', '13:35:00'),
(57, 5, 22, 0, 'Miércoles', '13:40:00', '14:25:00'),
(58, 5, 5, 0, 'Miércoles', '14:30:00', '15:15:00'),
(59, 5, 5, 0, 'Miércoles', '15:20:00', '16:05:00'),
(60, 5, 17, 0, 'Jueves', '07:50:00', '08:35:00'),
(61, 5, 17, 0, 'Jueves', '08:40:00', '09:25:00'),
(62, 5, 24, 0, 'Jueves', '09:30:00', '10:15:00'),
(63, 5, 24, 0, 'Jueves', '10:20:00', '11:55:00'),
(64, 5, 59, 0, 'Jueves', '14:30:00', '15:15:00'),
(65, 5, 59, 0, 'Jueves', '15:20:00', '16:05:00'),
(66, 5, 16, 0, 'Viernes', '07:00:00', '07:45:00'),
(67, 5, 24, 0, 'Viernes', '07:50:00', '08:35:00'),
(68, 5, 24, 0, 'Viernes', '08:40:00', '09:25:00'),
(69, 5, 23, 0, 'Viernes', '09:30:00', '10:15:00'),
(70, 5, 23, 0, 'Viernes', '10:20:00', '11:55:00'),
(71, 5, 19, 0, 'Viernes', '12:00:00', '12:45:00'),
(72, 5, 19, 0, 'Viernes', '12:50:00', '13:35:00'),
(73, 5, 19, 0, 'Viernes', '13:40:00', '14:25:00'),
(74, 5, 59, 0, 'Viernes', '14:30:00', '15:15:00'),
(75, 5, 59, 0, 'Viernes', '15:20:00', '16:05:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `IdRecurso` int NOT NULL,
  `nombre_Recurso` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdEspacio` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recursos`
--

INSERT INTO `recursos` (`IdRecurso`, `nombre_Recurso`, `IdEspacio`) VALUES
(1, 'Television', 1),
(2, 'computadora1', 7),
(3, 'HDMI cable', 6),
(4, 'Control aire acondicionado', 11),
(5, 'proyector', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `IdReserva` int NOT NULL,
  `IdEspacio` int NOT NULL,
  `Cedula_Docente` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`IdReserva`, `IdEspacio`, `Cedula_Docente`, `Fecha`, `Hora_Reserva`, `aprobada`) VALUES
(1, 14, '44444444', '2025-11-28', 8, 0),
(2, 3, '44444444', '2025-11-28', 13, 0),
(4, 12, '57015969', '2222-02-20', 9, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_recurso`
--

CREATE TABLE `reserva_recurso` (
  `IdReservaRecurso` int NOT NULL,
  `IdRecurso` int NOT NULL,
  `Cedula_Docente` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva_recurso`
--

INSERT INTO `reserva_recurso` (`IdReservaRecurso`, `IdRecurso`, `Cedula_Docente`, `Fecha`, `Hora_Reserva`, `aprobada`) VALUES
(1, 3, '83256953', '2025-11-19', 10, 1),
(2, 3, '83256953', '2025-11-07', 10, 0),
(3, 5, '83256953', '2025-11-05', 12, 0),
(4, 1, '83256953', '2025-12-04', 12, 1),
(5, 5, '83256953', '2025-11-07', 12, 0),
(6, 3, '44444444', '2025-11-06', 9, 0),
(7, 3, '57015969', '2222-02-22', 8, 0);

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
('28832784', '$2y$10$jlAR6EmnqwSkjfxtLkfZv.CD94q53f7AMhIgQa1y1CDzFMgZh1mJW', 'Facundo Rubil'),
('29755117', '$2y$10$Cpv0Mt9Jv1VbTf3p0SIoi.ZzaXGAEUROzuYTBG5lppYam3zy6utcW', 'Jrameau'),
('34247591', '$2y$10$4EvixTAmNx18UbIjiccmReY1az8dwvPPTeDwi2FMtmhkhZvLV5cJq', 'Martin Azambuja'),
('36129797', '$2y$10$7IGKwU85eZ8WROcIsbwxruhfsLZLnSpE2u5rWL1JRhKcxCFo56O1m', 'Franco Povea'),
('43153054', '$2y$10$9LILuf062XCCDAhNoPBOQe0zu/ascnLtPTX6RAf5B.RReWuaFJzoa', 'Bruno'),
('44444444', '$2y$10$ERQfTQLTbf.o68dwON48v.yyC9mbdg4rjqcNa5kFVqa6zPHmc5/9i', 'gentile gustavo'),
('46798807', '$2y$10$a9ZZ69/9GGgDj/M/lKAbsOtU.yeRMSTyfTPZsIMRFhoeTnYuIYFFK', 'Bruno'),
('4871419', '$2y$10$21qZYl4neRCdVtEY3Rz5UeIMWn40L0B6lBChT9fVywhyapzzsFrz6', 'Saldivia Agustin'),
('50072203', '$2y$10$KNsqukTCGPna3s5VEgevsOIt.FiUzJVuWFVyRxU2dtcuU0YGmGWdS', 'Agustin Piñeiro'),
('52836584', '$2y$10$..ZK1JlwRIDYRwGbeIREOu5Yy5CCR5SzMxuYX9lnI11Zuy11QCZG6', 'Bianchi Matias'),
('57015969', '$2y$10$Z6ivlBmp2FHoCCCKF2c1UulWOMR1XDIEWvOMXBTukuK/bc3hInXHy', 'Matias Bianchi'),
('57137379', '$2y$10$9dMxY5RCHdatwan8OFlIW.2sAb1HlP3oEiX9sJaDUM/KAK48j3ECG', 'Gustavo'),
('79538818', '$2y$10$Cpv0Mt9Jv1VbTf3p0SIoi.ZzaXGAEUROzuYTBG5lppYam3zy6utcW', 'Ana Ines Gonzalez'),
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
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`IdReserva`),
  ADD KEY `fk_reserva_docente` (`Cedula_Docente`);

--
-- Indices de la tabla `reserva_recurso`
--
ALTER TABLE `reserva_recurso`
  ADD PRIMARY KEY (`IdReservaRecurso`),
  ADD KEY `fk_reservaRec_docente` (`Cedula_Docente`);

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
  MODIFY `codigo_adm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `IdAsignatura` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `IdCurso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `codigo_doc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `espacios`
--
ALTER TABLE `espacios`
  MODIFY `IdEspacio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `IdRecurso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `IdReserva` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reserva_recurso`
--
ALTER TABLE `reserva_recurso`
  MODIFY `IdReservaRecurso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_docente` FOREIGN KEY (`Cedula_Docente`) REFERENCES `usuarios` (`Cedula`);

--
-- Filtros para la tabla `reserva_recurso`
--
ALTER TABLE `reserva_recurso`
  ADD CONSTRAINT `fk_reservaRec_docente` FOREIGN KEY (`Cedula_Docente`) REFERENCES `usuarios` (`Cedula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
