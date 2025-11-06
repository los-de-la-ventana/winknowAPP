-- TABLA: usuarios (Base)
-- ============================================
CREATE TABLE `usuarios` (
  `Cedula` VARCHAR(12) NOT NULL,
  `Contrasenia` VARCHAR(255) NOT NULL,
  `Nombre_usr` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: administrador
-- ============================================
CREATE TABLE `administrador` (
  `codigo_adm` INT NOT NULL AUTO_INCREMENT,
  `Cedula` VARCHAR(12) DEFAULT NULL,
  `rolAdmin` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`codigo_adm`),
  FOREIGN KEY (`Cedula`) REFERENCES `usuarios`(`Cedula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: docente
-- ============================================
CREATE TABLE `docente` (
  `codigo_doc` INT NOT NULL AUTO_INCREMENT,
  `Cedula` INT DEFAULT NULL,
  `contrasenia` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`codigo_doc`),
  UNIQUE KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: estudiante
-- ============================================
CREATE TABLE `estudiante` (
  `Cedula` INT NOT NULL,
  PRIMARY KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: cursos
-- ============================================
CREATE TABLE `cursos` (
  `IdCurso` INT NOT NULL AUTO_INCREMENT,
  `Cedula` INT NOT NULL,
  `Recursos_Pedidos` VARCHAR(100) DEFAULT NULL,
  `Nombre` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`IdCurso`),
  FOREIGN KEY (`Cedula`) REFERENCES `docente`(`Cedula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: grupo
-- ============================================
CREATE TABLE `grupo` (
  `IdGrupo` INT NOT NULL AUTO_INCREMENT,
  `nombreGrupo` VARCHAR(50) NOT NULL,
  `IdCurso` INT NOT NULL,
  `anio` INT DEFAULT NULL,
  PRIMARY KEY (`IdGrupo`),
  FOREIGN KEY (`IdCurso`) REFERENCES `cursos`(`IdCurso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: asignatura
-- ============================================
CREATE TABLE `asignatura` (
  `IdAsignatura` INT NOT NULL AUTO_INCREMENT,
  `nombreAsignatura` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`IdAsignatura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: horario
-- ============================================
CREATE TABLE `horario` (
  `ID_horario` INT NOT NULL AUTO_INCREMENT,
  `IdGrupo` INT NOT NULL,
  `IdAsignatura` INT NOT NULL,
  `Cedula` INT NOT NULL,
  `DiaSemana` VARCHAR(20) NOT NULL,
  `HoraInicio` TIME NOT NULL,
  `HoraFin` TIME NOT NULL,
  PRIMARY KEY (`ID_horario`),
  FOREIGN KEY (`IdGrupo`) REFERENCES `grupo`(`IdGrupo`) ON DELETE CASCADE,
  FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura`(`IdAsignatura`) ON DELETE CASCADE,
  FOREIGN KEY (`Cedula`) REFERENCES `docente`(`Cedula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ============================================
-- TABLA: espacios
-- ============================================
CREATE TABLE `espacios` (
  `IdEspacio` INT NOT NULL AUTO_INCREMENT,
  `NumSalon` INT DEFAULT NULL,
  `capacidad` INT DEFAULT NULL,
  `Tipo_salon` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`IdEspacio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: recursos
-- ============================================
CREATE TABLE `recursos` (
  `IdRecurso` INT NOT NULL AUTO_INCREMENT,
  `nombre_Recurso` VARCHAR(120) DEFAULT NULL,
  `IdEspacio` INT NOT NULL,
  PRIMARY KEY (`IdRecurso`),
  FOREIGN KEY (`IdEspacio`) REFERENCES `espacios`(`IdEspacio`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: reserva (espacios)
-- ============================================
CREATE TABLE `reserva` (
  `IdReserva` INT NOT NULL AUTO_INCREMENT,
  `IdEspacio` INT NOT NULL,
  `Cedula_Docente` VARCHAR(12) NOT NULL,
  `Fecha` DATE DEFAULT NULL,
  `Hora_Reserva` INT DEFAULT NULL,
  `aprobada` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IdReserva`),
  FOREIGN KEY (`IdEspacio`) REFERENCES `espacios`(`IdEspacio`) ON DELETE CASCADE,
  FOREIGN KEY (`Cedula_Docente`) REFERENCES `usuarios`(`Cedula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: reserva_recurso
-- ============================================
CREATE TABLE `reserva_recurso` (
  `IdReservaRecurso` INT NOT NULL AUTO_INCREMENT,
  `IdRecurso` INT NOT NULL,
  `Cedula_Docente` VARCHAR(12) NOT NULL,
  `Fecha` DATE DEFAULT NULL,
  `Hora_Reserva` INT DEFAULT NULL,
  `aprobada` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IdReservaRecurso`),
  FOREIGN KEY (`IdRecurso`) REFERENCES `recursos`(`IdRecurso`) ON DELETE CASCADE,
  FOREIGN KEY (`Cedula_Docente`) REFERENCES `usuarios`(`Cedula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLAS DE RELACIÓN (N:M)
-- ============================================

CREATE TABLE `asignatura_curso` (
  `IdAsignatura` INT NOT NULL,
  `IdCurso` INT NOT NULL,
  PRIMARY KEY (`IdAsignatura`, `IdCurso`),
  FOREIGN KEY (`IdAsignatura`) REFERENCES `asignatura`(`IdAsignatura`) ON DELETE CASCADE,
  FOREIGN KEY (`IdCurso`) REFERENCES `cursos`(`IdCurso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dictan` (
  `Cedula` INT NOT NULL,
  `IdCurso` INT NOT NULL,
  PRIMARY KEY (`Cedula`, `IdCurso`),
  FOREIGN KEY (`Cedula`) REFERENCES `docente`(`Cedula`) ON DELETE CASCADE,
  FOREIGN KEY (`IdCurso`) REFERENCES `cursos`(`IdCurso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `estudiante_curso` (
  `Cedula` INT NOT NULL,
  `IdCurso` INT NOT NULL,
  PRIMARY KEY (`Cedula`, `IdCurso`),
  FOREIGN KEY (`Cedula`) REFERENCES `estudiante`(`Cedula`) ON DELETE CASCADE,
  FOREIGN KEY (`IdCurso`) REFERENCES `cursos`(`IdCurso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `docente_recurso` (
  `IdRecurso` INT NOT NULL,
  `Cedula` INT NOT NULL,
  PRIMARY KEY (`IdRecurso`, `Cedula`),
  FOREIGN KEY (`IdRecurso`) REFERENCES `recursos`(`IdRecurso`) ON DELETE CASCADE,
  FOREIGN KEY (`Cedula`) REFERENCES `docente`(`Cedula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
