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
// PROCESAR APROBACIÓN DE RESERVA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);
    
    // Por ahora solo mostramos mensaje de éxito
    // Puedes agregar un campo "estado" a la tabla Reserva si quieres guardar el estado
    $_SESSION['mensaje'] = "Reserva aprobada exitosamente.";
    $_SESSION['tipo_mensaje'] = "exito";
    
    header("Location: administrar_reservas_espacios.php");
    exit;
}

// ============================================
// PROCESAR RECHAZO DE RESERVA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechazar_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);
    
    $sqlDelete = "DELETE FROM Reserva WHERE IdReserva = ?";
    $stmtDelete = $mysqli->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idReserva);
    
    if ($stmtDelete->execute()) {
        $_SESSION['mensaje'] = "Reserva rechazada y eliminada exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al rechazar la reserva: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtDelete->close();
    
    header("Location: administrar_reservas_espacios.php");
    exit;
}

// ============================================
// OBTENER TODAS LAS RESERVAS ACTIVAS
// ============================================
$fechaActual = date('Y-m-d');
$queryReservas = "SELECT r.IdReserva, r.Fecha, r.Hora_Reserva, 
                         e.NumSalon, e.Tipo_salon, e.capacidad,
                         e.IdEspacio
                  FROM Reserva r
                  INNER JOIN Espacios e ON r.IdEspacio = e.IdEspacio
                  WHERE r.Fecha >= ?
                  ORDER BY r.Fecha ASC, r.Hora_Reserva ASC";
$stmtReservas = $mysqli->prepare($queryReservas);
$stmtReservas->bind_param("s", $fechaActual);
$stmtReservas->execute();
$resultReservas = $stmtReservas->get_result();

// ============================================
// ESTADÍSTICAS
// ============================================
$sqlStats = "SELECT COUNT(*) as total FROM Reserva WHERE Fecha >= ?";
$stmtStats = $mysqli->prepare($sqlStats);
$stmtStats->bind_param("s", $fechaActual);
$stmtStats->execute();
$resultStats = $stmtStats->get_result();
$totalReservas = $resultStats->fetch_assoc()['total'];

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================
function obtenerNombreEspacio($tipo, $numSalon) {
    return match ($tipo) {
        'Taller' => 'Taller ' . $numSalon,
        'Salon'  => 'Salón ' . $numSalon,
        'Laboratorio' => 'Laboratorio ' . $numSalon,
        default  => 'Aula ' . $numSalon
    };
}

function obtenerIconoTipo($tipoSalon) {
    return match ($tipoSalon) {
        'Taller' => 'bi-tools',
        'Salon'  => 'bi-building',
        'Laboratorio' => 'bi-flask',
        default  => 'bi-door-open'
    };
}

function formatearFecha($fecha) {
    $fechaObj = new DateTime($fecha);
    return $fechaObj->format('d/m/Y');
}

function formatearHora($hora) {
    return $hora . ':00 hs';
}

// ============================================
// INCLUIR VISTA HTML
// ============================================
include '../front/header.html';
include '../front/admin_reservasESP_html.php';
include '../front/navADM.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>