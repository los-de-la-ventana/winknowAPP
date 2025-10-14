<?php
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// SEGURIDAD: VERIFICAR PERMISOS DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// ============================================
// OBTENER TODOS LOS CURSOS CON SUS DATOS
// ============================================
$queryCursos = "SELECT 
    c.IdCurso,
    c.Nombre as nombre_curso,
    u.Nombre_usr as nombre_docente,
    u.Cedula as cedula_docente,
    GROUP_CONCAT(DISTINCT a.nombreAsignatura SEPARATOR '|') as asignaturas
    FROM cursos c
    INNER JOIN usuarios u ON c.Cedula = u.Cedula
    LEFT JOIN asignatura_curso ac ON c.IdCurso = ac.IdCurso
    LEFT JOIN asignatura a ON ac.IdAsignatura = a.IdAsignatura
    GROUP BY c.IdCurso
    ORDER BY c.Nombre";
$resultCursos = $mysqli->query($queryCursos);

// ============================================
// OBTENER HORARIOS DE TODOS LOS CURSOS
// ============================================
$queryHorarios = "SELECT 
    h.ID_horario,
    h.Hora,
    h.Dia,
    h.IdCurso,
    c.Nombre as nombre_curso,
    u.Nombre_usr as nombre_docente,
    GROUP_CONCAT(DISTINCT a.nombreAsignatura SEPARATOR ', ') as asignaturas
    FROM horario h
    INNER JOIN cursos c ON h.IdCurso = c.IdCurso
    INNER JOIN usuarios u ON h.Cedula = u.Cedula
    LEFT JOIN asignatura_curso ac ON c.IdCurso = ac.IdCurso
    LEFT JOIN asignatura a ON ac.IdAsignatura = a.IdAsignatura
    GROUP BY h.ID_horario
    ORDER BY h.Dia, h.Hora";
$resultHorarios = $mysqli->query($queryHorarios);

// Organizar horarios por día de la semana y hora
$horariosSemana = array();
$diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

// Inicializar estructura de horarios
foreach ($diasSemana as $dia) {
    $horariosSemana[$dia] = array();
    for ($hora = 7; $hora <= 22; $hora++) {
        $horariosSemana[$dia][$hora] = array();
    }
}

// Llenar horarios con los datos de la BD
if ($resultHorarios && $resultHorarios->num_rows > 0) {
    while ($horario = $resultHorarios->fetch_assoc()) {
        // Obtener día de la semana de la fecha
        $fecha = new DateTime($horario['Dia']);
        $numeroDia = $fecha->format('N'); // 1 (Lunes) a 7 (Domingo)
        
        if ($numeroDia <= 5) { // Solo días laborables
            $diaNombre = $diasSemana[$numeroDia - 1];
            
            // Obtener hora
            $horaTime = new DateTime($horario['Hora']);
            $horaNum = (int)$horaTime->format('H');
            
            if ($horaNum >= 7 && $horaNum <= 22) {
                $horariosSemana[$diaNombre][$horaNum][] = array(
                    'nombre_curso' => $horario['nombre_curso'],
                    'docente' => $horario['nombre_docente'],
                    'asignaturas' => $horario['asignaturas']
                );
            }
        }
    }
}

// ============================================
// ESTADÍSTICAS
// ============================================
$sqlStats = "SELECT 
    (SELECT COUNT(*) FROM cursos) as total_cursos,
    (SELECT COUNT(*) FROM asignatura) as total_asignaturas,
    (SELECT COUNT(*) FROM horario) as total_horarios";
$resultStats = $mysqli->query($sqlStats);
$stats = $resultStats->fetch_assoc();

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================
function obtenerColorCurso($index) {
    $colores = [
        '#4f7df3', // Azul
        '#10b981', // Verde
        '#f59e0b', // Amarillo
        '#ef4444', // Rojo
        '#8b5cf6', // Púrpura
        '#ec4899', // Rosa
        '#06b6d4', // Cyan
        '#f97316'  // Naranja
    ];
    return $colores[$index % count($colores)];
}

// ============================================
// INCLUIR VISTA HTML
// ============================================
include '../front/header.html';
include '../front/admCalendario_html.php';
include '../front/navADM.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>