-- Base de datos: db_WinKnow
-- Versión limpia con Foreign Keys y AUTO_INCREMENT

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- TABLA: usuarios (Base principal)
-- --------------------------------------------------------
CREATE TABLE `usuarios` (
  `Cedula` varchar(12) NOT NULL,
  `Contrasenia` varchar(255) NOT NULL,
  `Nombre_usr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: administrador
-- --------------------------------------------------------
CREATE TABLE `administrador` (
  `codigo_adm` int NOT NULL AUTO_INCREMENT,
  `Cedula` varchar(12) DEFAULT NULL,
  `rolAdmin` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codigo_adm`),
  KEY `Cedula` (`Cedula`),
  CONSTRAINT `fk_administrador_usuario` FOREIGN KEY (`Cedula`) REFERENCES `usuarios` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=31;

-- --------------------------------------------------------
-- TABLA: docente
-- --------------------------------------------------------
CREATE TABLE `docente` (
  `codigo_doc` int NOT NULL AUTO_INCREMENT,
  `Cedula` int DEFAULT NULL,
  `contrasenia` varchar(255) NOT NULL,
  PRIMARY KEY (`codigo_doc`),
  UNIQUE KEY `Cedula` (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=21;

-- --------------------------------------------------------
-- TABLA: estudiante
-- --------------------------------------------------------
CREATE TABLE `estudiante` (
  `Cedula` int NOT NULL,
  PRIMARY KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: email
-- --------------------------------------------------------
CREATE TABLE `email` (
  `Cedula` int NOT NULL,
  `numeroTelefono` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`Cedula`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: cursos
-- --------------------------------------------------------
CREATE TABLE `cursos` (
  `IdCurso` int NOT NULL,
  `Cedula` int NOT NULL,
  `Recursos_Pedidos` varchar(100) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdCurso`),
  KEY `Cedula` (`Cedula`),
  CONSTRAINT `fk_cursos_docente` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: secundario
-- --------------------------------------------------------
CREATE TABLE `secundario` (
  `IdCurso` int NOT NULL,
  `anio` int DEFAULT NULL,
  PRIMARY KEY (`IdCurso`),
  CONSTRAINT `fk_secundario_curso` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: terciario
-- --------------------------------------------------------
CREATE TABLE `terciario` (
  `IdCurso` int NOT NULL,
  `NumSemestres` int DEFAULT NULL,
  PRIMARY KEY (`IdCurso`),
  CONSTRAINT `fk_terciario_curso` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: grupo
-- --------------------------------------------------------
CREATE TABLE `grupo` (
  `IdGrupo` int NOT NULL AUTO_INCREMENT,
  `nombreGrupo` varchar(50) NOT NULL,
  `IdCurso` int NOT NULL,
  `anio` int DEFAULT NULL,
  PRIMARY KEY (`IdGrupo`),
  KEY `IdCurso` (`IdCurso`),
  CONSTRAINT `fk_grupo_curso` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=6;

-- --------------------------------------------------------
-- TABLA: asignatura
-- --------------------------------------------------------
CREATE TABLE `asignatura` (
  `IdAsignatura` int NOT NULL AUTO_INCREMENT,
  `nombreAsignatura` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdAsignatura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=80;

-- --------------------------------------------------------
-- TABLA: asignatura_curso (Relación N:M)
-- --------------------------------------------------------
CREATE TABLE `asignatura_curso` (
  `IdAsignatura` int NOT NULL,
  `IdCurso` int NOT NULL,
  PRIMARY KEY (`IdAsignatura`,`IdCurso`),
  KEY `IdCurso` (`IdCurso`),
  CONSTRAINT `fk_asignatura_curso_asignatura` FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura` (`IdAsignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_asignatura_curso_curso` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: dictan (Relación N:M)
-- --------------------------------------------------------
CREATE TABLE `dictan` (
  `Cedula` int NOT NULL,
  `IdCurso` int NOT NULL,
  PRIMARY KEY (`Cedula`,`IdCurso`),
  KEY `IdCurso` (`IdCurso`),
  CONSTRAINT `fk_dictan_docente` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dictan_curso` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: estudiante_curso (Relación N:M)
-- --------------------------------------------------------
CREATE TABLE `estudiante_curso` (
  `Cedula` int NOT NULL,
  `IdCurso` int NOT NULL,
  PRIMARY KEY (`Cedula`,`IdCurso`),
  KEY `IdCurso` (`IdCurso`),
  CONSTRAINT `fk_estudiante_curso_estudiante` FOREIGN KEY (`Cedula`) REFERENCES `estudiante` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_estudiante_curso_curso` FOREIGN KEY (`IdCurso`) REFERENCES `cursos` (`IdCurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: horario
-- --------------------------------------------------------
CREATE TABLE `horario` (
  `ID_horario` int NOT NULL AUTO_INCREMENT,
  `IdGrupo` int NOT NULL,
  `IdAsignatura` int NOT NULL,
  `Cedula` int NOT NULL,
  `DiaSemana` varchar(20) NOT NULL,
  `HoraInicio` int NOT NULL,
  `HoraFin` int NOT NULL,
  PRIMARY KEY (`ID_horario`),
  KEY `IdGrupo` (`IdGrupo`),
  KEY `IdAsignatura` (`IdAsignatura`),
  KEY `Cedula` (`Cedula`),
  CONSTRAINT `fk_horario_grupo` FOREIGN KEY (`IdGrupo`) REFERENCES `grupo` (`IdGrupo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_horario_asignatura` FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura` (`IdAsignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_horario_docente` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=76;

-- --------------------------------------------------------
-- TABLA: espacios
-- --------------------------------------------------------
CREATE TABLE `espacios` (
  `IdEspacio` int NOT NULL AUTO_INCREMENT,
  `NumSalon` int DEFAULT NULL,
  `capacidad` int DEFAULT NULL,
  `Tipo_salon` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`IdEspacio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=16;

-- --------------------------------------------------------
-- TABLA: recursos
-- --------------------------------------------------------
CREATE TABLE `recursos` (
  `IdRecurso` int NOT NULL AUTO_INCREMENT,
  `nombre_Recurso` varchar(120) DEFAULT NULL,
  `IdEspacio` int NOT NULL,
  PRIMARY KEY (`IdRecurso`),
  KEY `IdEspacio` (`IdEspacio`),
  CONSTRAINT `fk_recursos_espacio` FOREIGN KEY (`IdEspacio`) REFERENCES `espacios` (`IdEspacio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- --------------------------------------------------------
-- TABLA: docente_recurso (Relación N:M)
-- --------------------------------------------------------
CREATE TABLE `docente_recurso` (
  `IdRecurso` int NOT NULL,
  `Cedula` int NOT NULL,
  PRIMARY KEY (`IdRecurso`,`Cedula`),
  KEY `Cedula` (`Cedula`),
  CONSTRAINT `fk_docente_recurso_recurso` FOREIGN KEY (`IdRecurso`) REFERENCES `recursos` (`IdRecurso`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_docente_recurso_docente` FOREIGN KEY (`Cedula`) REFERENCES `docente` (`Cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLA: reserva
-- --------------------------------------------------------
CREATE TABLE `reserva` (
  `IdReserva` int NOT NULL AUTO_INCREMENT,
  `IdEspacio` int NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL,
  PRIMARY KEY (`IdReserva`),
  KEY `IdEspacio` (`IdEspacio`),
  CONSTRAINT `fk_reserva_espacio` FOREIGN KEY (`IdEspacio`) REFERENCES `espacios` (`IdEspacio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- --------------------------------------------------------
-- TABLA: reserva_recurso
-- --------------------------------------------------------
CREATE TABLE `reserva_recurso` (
  `IdReservaRecurso` int NOT NULL AUTO_INCREMENT,
  `IdRecurso` int NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora_Reserva` int DEFAULT NULL,
  `aprobada` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdReservaRecurso`),
  KEY `IdRecurso` (`IdRecurso`),
  CONSTRAINT `fk_reserva_recurso_recurso` FOREIGN KEY (`IdRecurso`) REFERENCES `recursos` (`IdRecurso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

COMMIT;