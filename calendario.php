<?php
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// SEGURIDAD: VERIFICAR PERMISOS
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login_reg/login.php");
    exit;
}

// ============================================
// OBTENER TODOS LOS GRUPOS DISPONIBLES
// ============================================
$queryGrupos = "SELECT g.IdGrupo, g.nombreGrupo, c.Nombre as nombre_curso, g.anio
    FROM grupo g
    INNER JOIN cursos c ON g.IdCurso = c.IdCurso
    ORDER BY c.Nombre, g.nombreGrupo";
$resultGrupos = $mysqli->query($queryGrupos);

// ============================================
// OBTENER GRUPO SELECCIONADO
// ============================================
$grupoSeleccionado = isset($_GET['grupo']) ? intval($_GET['grupo']) : null;

// Si no hay grupo seleccionado, usar el primero disponible
if ($grupoSeleccionado === null && $resultGrupos->num_rows > 0) {
    $resultGrupos->data_seek(0);
    $primerGrupo = $resultGrupos->fetch_assoc();
    $grupoSeleccionado = $primerGrupo['IdGrupo'];
    $resultGrupos->data_seek(0);
}

// ============================================
// OBTENER INFORMACIÓN DEL GRUPO SELECCIONADO
// ============================================
$infoGrupo = null;
if ($grupoSeleccionado) {
    $queryInfo = "SELECT g.IdGrupo, g.nombreGrupo, g.anio, c.Nombre as nombre_curso
        FROM grupo g
        INNER JOIN cursos c ON g.IdCurso = c.IdCurso
        WHERE g.IdGrupo = ?";
    $stmtInfo = $mysqli->prepare($queryInfo);
    $stmtInfo->bind_param("i", $grupoSeleccionado);
    $stmtInfo->execute();
    $resultInfo = $stmtInfo->get_result();
    $infoGrupo = $resultInfo->fetch_assoc();
    $stmtInfo->close();
}

// ============================================
// OBTENER HORARIOS DEL GRUPO SELECCIONADO
// ============================================
$horariosSemana = array();
$diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
$horasDisponibles = range(7, 22);

// Inicializar estructura de horarios
foreach ($diasSemana as $dia) {
    $horariosSemana[$dia] = array();
    foreach ($horasDisponibles as $hora) {
        $horariosSemana[$dia][$hora] = null;
    }
}

// Llenar horarios con los datos de la BD
if ($grupoSeleccionado) {
    $queryHorarios = "SELECT h.DiaSemana, h.HoraInicio, h.HoraFin, a.nombreAsignatura
        FROM horario h
        INNER JOIN asignatura a ON h.IdAsignatura = a.IdAsignatura
        WHERE h.IdGrupo = ?
        ORDER BY h.DiaSemana, h.HoraInicio";
    
    $stmtHorarios = $mysqli->prepare($queryHorarios);
    $stmtHorarios->bind_param("i", $grupoSeleccionado);
    $stmtHorarios->execute();
    $resultHorarios = $stmtHorarios->get_result();
    
    while ($horario = $resultHorarios->fetch_assoc()) {
        $dia = $horario['DiaSemana'];
        $horaInicio = intval($horario['HoraInicio']);
        
        // Asignar horario a la estructura
        $horariosSemana[$dia][$horaInicio] = array(
            'asignatura' => $horario['nombreAsignatura'],
            'horaFin' => intval($horario['HoraFin'])
        );
    }
    $stmtHorarios->close();
}

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================
function obtenerColorAsignatura($index) {
    $colores = [
        '#131942ff'
    ];
    return $colores[$index % count($colores)];
}

// ============================================
// INCLUIR VISTA HTML
// ============================================
include '../front/header.html';
include '../front/calendario_html.php';

if ($_SESSION['tipo'] === 'admin') {
    include '../front/navADM.php';
} elseif ($_SESSION['tipo'] === 'docente') {
    include '../front/navDOC.php';
} else {
    include '../front/navEST.php';
}

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>