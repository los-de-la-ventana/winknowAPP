-- ==============================================
-- TABLA PRINCIPAL: Usuarios
-- ==============================================
CREATE TABLE Usuarios (
    Cedula INT PRIMARY KEY,
    Contrasenia VARCHAR(255) NOT NULL,   
    Nombre_usr VARCHAR(50)
) ENGINE=InnoDB;

-- ==============================================
-- Email (Depende de Usuarios)
-- ==============================================
CREATE TABLE Email (
    Cedula INT NOT NULL,
    numeroTelefono VARCHAR(50),
    email VARCHAR(50),
    PRIMARY KEY (Cedula, email),
    FOREIGN KEY (Cedula) REFERENCES Usuarios(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Docente (Depende de Usuarios)
-- ==============================================
CREATE TABLE Docente (
    codigo_doc INT PRIMARY KEY AUTO_INCREMENT,
    Cedula INT UNIQUE,
    grado INT,
    contrasenia VARCHAR(255) NOT NULL,
    AnioInsercion DATE,
    Estado VARCHAR(20),
    FOREIGN KEY (Cedula) REFERENCES Usuarios(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Administrador (Depende de Usuarios)
-- ==============================================
CREATE TABLE Administrador (
    codigo_adm INT PRIMARY KEY AUTO_INCREMENT,
    Cedula INT UNIQUE,
    EsAdmin BOOLEAN,
    rolAdmin VARCHAR(100),
    FOREIGN KEY (Cedula) REFERENCES Usuarios(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Estudiante (Depende de Usuarios)
-- ==============================================
CREATE TABLE Estudiante (
    Cedula INT PRIMARY KEY,
    FechaNac DATE,
    FOREIGN KEY (Cedula) REFERENCES Usuarios(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Espacios (Salones, aulas, etc.)
-- ==============================================
CREATE TABLE Espacios (
    IdEspacio INT PRIMARY KEY AUTO_INCREMENT,
    NumEdificio INT,
    NumSalon INT,
    capacidad INT,
    Estado_Espacio VARCHAR(20),
    Tipo_salon VARCHAR(30)
) ENGINE=InnoDB;

-- ==============================================
-- Recursos (Depende de Espacios)
-- ==============================================
CREATE TABLE Recursos (
    IdRecurso INT PRIMARY KEY AUTO_INCREMENT,
    nombre_Recurso VARCHAR(120),
    IdEspacio INT NOT NULL,
    FOREIGN KEY (IdEspacio) REFERENCES Espacios(IdEspacio)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Reserva (Depende de Espacios)
-- ==============================================
CREATE TABLE Reserva (
    IdReserva INT PRIMARY KEY AUTO_INCREMENT,
    IdEspacio INT NOT NULL,
    Fecha DATE,
    Hora_Reserva INT,
    FOREIGN KEY (IdEspacio) REFERENCES Espacios(IdEspacio)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Cursos (Depende de Docente)
-- ==============================================
CREATE TABLE Cursos (
    IdCurso INT PRIMARY KEY AUTO_INCREMENT,
    Cedula INT NOT NULL, -- docente responsable
    Recursos_Pedidos VARCHAR(100),
    Orientacion VARCHAR(50),
    FOREIGN KEY (Cedula) REFERENCES Docente(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Dictan (Docente dicta Cursos)
-- ==============================================
CREATE TABLE Dictan (
    Cedula INT NOT NULL,
    IdCurso INT NOT NULL,
    PRIMARY KEY (Cedula, IdCurso),
    FOREIGN KEY (Cedula) REFERENCES Docente(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (IdCurso) REFERENCES Cursos(IdCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Estudiante inscrito en Curso
-- ==============================================
CREATE TABLE Estudiante_Curso (
    Cedula INT NOT NULL,
    IdCurso INT NOT NULL,
    PRIMARY KEY (Cedula, IdCurso),
    FOREIGN KEY (Cedula) REFERENCES Estudiante(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (IdCurso) REFERENCES Cursos(IdCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Docente solicita Recursos
-- ==============================================
CREATE TABLE Docente_Recurso (
    IdRecurso INT NOT NULL,
    Cedula INT NOT NULL,
    PRIMARY KEY (IdRecurso, Cedula),
    FOREIGN KEY (IdRecurso) REFERENCES Recursos(IdRecurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Cedula) REFERENCES Docente(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Asignaturas
-- ==============================================
CREATE TABLE Asignatura (
    IdAsignatura INT PRIMARY KEY AUTO_INCREMENT,
    nombreAsignatura VARCHAR(50)
) ENGINE=InnoDB;

-- ==============================================
-- Asignatura en un Curso
-- ==============================================
CREATE TABLE Asignatura_Curso (
    IdAsignatura INT NOT NULL,
    IdCurso INT NOT NULL,
    PRIMARY KEY (IdAsignatura, IdCurso),
    FOREIGN KEY (IdAsignatura) REFERENCES Asignatura(IdAsignatura)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (IdCurso) REFERENCES Cursos(IdCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==========r====================================
-- Secundario (Depende de Cursos)
-- ==============================================
CREATE TABLE Secundario (
    IdCurso INT PRIMARY KEY,
    anio INT,
    FOREIGN KEY (IdCurso) REFERENCES Cursos(IdCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Terciario (Depende de Cursos)
-- ==============================================
CREATE TABLE Terciario (
    IdCurso INT PRIMARY KEY,
    NumSemestres INT,
    FOREIGN KEY (IdCurso) REFERENCES Cursos(IdCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Horarios (Última tabla, depende de Cursos y Docentes)
-- ==============================================
CREATE TABLE Horario (
    ID_horario INT PRIMARY KEY AUTO_INCREMENT,
    Hora TIME,
    Dia DATE,
    IdCurso INT NOT NULL,
    Cedula INT NOT NULL,
    FOREIGN KEY (IdCurso) REFERENCES Cursos(IdCurso)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Cedula) REFERENCES Docente(Cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
