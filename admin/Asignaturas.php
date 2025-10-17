<?php
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// SEGURIDAD: VERIFICAR PERMISOS DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// Variables para mensajes
$mensaje = '';
$tipo_mensaje = '';

// ============================================
// PROCESAR REGISTRO DE NUEVA ASIGNATURA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_asignatura'])) {
    $nombreAsignatura = trim($_POST['nombre_asignatura']);
    
    if (empty($nombreAsignatura)) {
        $_SESSION['mensaje'] = "El nombre de la asignatura es obligatorio.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        // Verificar si ya existe
        $sqlCheck = "SELECT IdAsignatura FROM asignatura WHERE nombreAsignatura = ?";
        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $nombreAsignatura);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        
        if ($stmtCheck->num_rows > 0) {
            $_SESSION['mensaje'] = "Ya existe una asignatura con ese nombre.";
            $_SESSION['tipo_mensaje'] = "error";
        } else {
            $sqlInsert = "INSERT INTO asignatura (nombreAsignatura) VALUES (?)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $stmtInsert->bind_param("s", $nombreAsignatura);
            
            if ($stmtInsert->execute()) {
                $_SESSION['mensaje'] = "Asignatura creada exitosamente.";
                $_SESSION['tipo_mensaje'] = "exito";
            } else {
                $_SESSION['mensaje'] = "Error al crear la asignatura: " . $mysqli->error;
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmtInsert->close();
        }
        $stmtCheck->close();
    }
    
    header("Location: asignaturas.php");
    exit;
}

// ============================================
// PROCESAR CREACIÓN DE CURSO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_curso'])) {
    $nombreCurso = trim($_POST['nombre_curso']);
    $cedulaDocente = intval($_POST['cedula_docente']);
    $asignaturasSeleccionadas = $_POST['asignaturas'] ?? [];
    
    if (empty($nombreCurso) || $cedulaDocente <= 0) {
        $_SESSION['mensaje'] = "El nombre del curso y el docente son obligatorios.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        $mysqli->begin_transaction();
        
        try {
            // Insertar curso
            $sqlCurso = "INSERT INTO cursos (Cedula, Nombre) VALUES (?, ?)";
            $stmtCurso = $mysqli->prepare($sqlCurso);
            $stmtCurso->bind_param("is", $cedulaDocente, $nombreCurso);
            $stmtCurso->execute();
            $idCurso = $mysqli->insert_id;
            $stmtCurso->close();
            
            // Insertar en dictan (relación docente-curso)
            $sqlDictan = "INSERT INTO dictan (Cedula, IdCurso) VALUES (?, ?)";
            $stmtDictan = $mysqli->prepare($sqlDictan);
            $stmtDictan->bind_param("ii", $cedulaDocente, $idCurso);
            $stmtDictan->execute();
            $stmtDictan->close();
            
            // Asignar asignaturas al curso
            if (!empty($asignaturasSeleccionadas)) {
                $sqlAsigCurso = "INSERT INTO asignatura_curso (IdAsignatura, IdCurso) VALUES (?, ?)";
                $stmtAsigCurso = $mysqli->prepare($sqlAsigCurso);
                
                foreach ($asignaturasSeleccionadas as $idAsignatura) {
                    $stmtAsigCurso->bind_param("ii", $idAsignatura, $idCurso);
                    $stmtAsigCurso->execute();
                }
                $stmtAsigCurso->close();
            }
            
            $mysqli->commit();
            $_SESSION['mensaje'] = "Curso creado exitosamente.";
            $_SESSION['tipo_mensaje'] = "exito";
            
        } catch (Exception $e) {
            $mysqli->rollback();
            $_SESSION['mensaje'] = "Error al crear el curso: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "error";
        }
    }
    
    header("Location: asignaturas.php");
    exit;
}

// ============================================
// PROCESAR ELIMINACIÓN DE ASIGNATURA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_asignatura'])) {
    $idAsignatura = intval($_POST['id_asignatura']);
    
    // Verificar si hay cursos asociados
    $sqlCheck = "SELECT COUNT(*) as total FROM asignatura_curso WHERE IdAsignatura = ?";
    $stmtCheck = $mysqli->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $idAsignatura);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $row = $resultCheck->fetch_assoc();
    
    if ($row['total'] > 0) {
        $_SESSION['mensaje'] = "No se puede eliminar la asignatura porque tiene cursos asociados.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        $sqlDelete = "DELETE FROM asignatura WHERE IdAsignatura = ?";
        $stmtDelete = $mysqli->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $idAsignatura);
        
        if ($stmtDelete->execute()) {
            $_SESSION['mensaje'] = "Asignatura eliminada exitosamente.";
            $_SESSION['tipo_mensaje'] = "exito";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar la asignatura: " . $mysqli->error;
            $_SESSION['tipo_mensaje'] = "error";
        }
        $stmtDelete->close();
    }
    $stmtCheck->close();
    
    header("Location: asignaturas.php");
    exit;
}

// ============================================
// PROCESAR ELIMINACIÓN DE CURSO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_curso'])) {
    $idCurso = intval($_POST['id_curso']);
    
    $sqlDelete = "DELETE FROM cursos WHERE IdCurso = ?";
    $stmtDelete = $mysqli->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idCurso);
    
    if ($stmtDelete->execute()) {
        $_SESSION['mensaje'] = "Curso eliminado exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el curso: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtDelete->close();
    
    header("Location: asignaturas.php");
    exit;
}

// ============================================
// OBTENER ESTADÍSTICAS
// ============================================
$sqlStats = "SELECT 
    (SELECT COUNT(*) FROM asignatura) as total_asignaturas,
    (SELECT COUNT(*) FROM cursos) as total_cursos,
    (SELECT COUNT(DISTINCT Cedula) FROM cursos) as docentes_activos";
$resultStats = $mysqli->query($sqlStats);
$stats = $resultStats->fetch_assoc();

// ============================================
// OBTENER TODAS LAS ASIGNATURAS
// ============================================
$queryAsignaturas = "SELECT a.IdAsignatura, a.nombreAsignatura,
    COUNT(DISTINCT ac.IdCurso) as num_cursos
    FROM asignatura a
    LEFT JOIN asignatura_curso ac ON a.IdAsignatura = ac.IdAsignatura
    GROUP BY a.IdAsignatura
    ORDER BY a.nombreAsignatura";
$resultAsignaturas = $mysqli->query($queryAsignaturas);

// ============================================
// OBTENER TODOS LOS CURSOS CON SUS RELACIONES
// ============================================
$queryCursos = "SELECT c.IdCurso, c.Nombre as nombre_curso,
    u.Nombre_usr as nombre_docente, u.Cedula,
    GROUP_CONCAT(a.nombreAsignatura SEPARATOR ', ') as asignaturas
    FROM cursos c
    INNER JOIN usuarios u ON c.Cedula = u.Cedula
    LEFT JOIN asignatura_curso ac ON c.IdCurso = ac.IdCurso
    LEFT JOIN asignatura a ON ac.IdAsignatura = a.IdAsignatura
    GROUP BY c.IdCurso
    ORDER BY c.Nombre";
$resultCursos = $mysqli->query($queryCursos);

// ============================================
// OBTENER DOCENTES PARA EL SELECTOR
// ============================================
$queryDocentes = "SELECT u.Cedula, u.Nombre_usr 
    FROM usuarios u
    INNER JOIN docente d ON u.Cedula = d.Cedula
    ORDER BY u.Nombre_usr";
$resultDocentes = $mysqli->query($queryDocentes);

// ============================================
// OBTENER ASIGNATURAS PARA EL SELECTOR
// ============================================
$queryAsignaturasSelector = "SELECT IdAsignatura, nombreAsignatura FROM asignatura ORDER BY nombreAsignatura";
$resultAsignaturasSelector = $mysqli->query($queryAsignaturasSelector);
    
// ============================================
// INCLUIR VISTA HTML
// ============================================
include '../front/header.html';
include '../front/asignaturas_html.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>