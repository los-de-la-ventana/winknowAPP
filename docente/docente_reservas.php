<?php
// INICIO DE SESIÓN Y CONFIGURACIÓN
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// SEGURIDAD: VERIFICAR PERMISOS DE DOCENTE
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'docente') {
    header("Location: ../login_reg/login.php");
    exit;
}

// ============================================
// PROCESAR CREACIÓN DE RESERVA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_reserva'])) {
    $idEspacio = intval($_POST['id_espacio']);
    $fecha = $_POST['fecha'];
    $horaReserva = intval($_POST['hora_reserva']);
    
    // Validar que la fecha no sea pasada
    $fechaActual = date('Y-m-d');
    if ($fecha < $fechaActual) {
        $_SESSION['mensaje'] = "No se puede reservar una fecha pasada.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        // Verificar si ya existe una reserva para ese espacio, fecha y hora
        $sqlCheck = "SELECT COUNT(*) as total FROM reserva 
                     WHERE IdEspacio = ? AND Fecha = ? AND Hora_Reserva = ?";
        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtCheck->bind_param("isi", $idEspacio, $fecha, $horaReserva);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $row = $resultCheck->fetch_assoc();
        
        if ($row['total'] > 0) {
            $_SESSION['mensaje'] = "Ya existe una reserva para ese espacio en esa fecha y hora.";
            $_SESSION['tipo_mensaje'] = "error";
        } else {
            // Insertar la reserva
            $sqlInsert = "INSERT INTO reserva (IdEspacio, Fecha, Hora_Reserva) VALUES (?, ?, ?)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $stmtInsert->bind_param("isi", $idEspacio, $fecha, $horaReserva);
            
            if ($stmtInsert->execute()) {
                $_SESSION['mensaje'] = "Reserva creada exitosamente.";
                $_SESSION['tipo_mensaje'] = "exito";
            } else {
                $_SESSION['mensaje'] = "Error al crear la reserva: " . $mysqli->error;
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmtInsert->close();
        }
        $stmtCheck->close();
    }
    
    header("Location: docente_reservas.php");
    exit;
}

// ============================================
// PROCESAR ELIMINACIÓN DE RESERVA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);
    
    $sqlDelete = "DELETE FROM reserva WHERE IdReserva = ?";
    $stmtDelete = $mysqli->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idReserva);
    
    if ($stmtDelete->execute()) {
        $_SESSION['mensaje'] = "Reserva eliminada exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la reserva: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtDelete->close();
    
    header("Location: docente_reservas.php");
    exit;
}

// ============================================
// OBTENER DATOS
// ============================================

// Obtener todos los espacios para el selector
$queryEspacios = "SELECT * FROM espacios ORDER BY NumSalon";
$resultEspacios = $mysqli->query($queryEspacios);

// ============================================
// OBTENER RESERVAS ACTIVAS (FUTURAS Y ACTUALES)
// ============================================
$fechaActual = date('Y-m-d');
$queryReservas = "SELECT r.IdReserva, r.Fecha, r.Hora_Reserva, 
                         e.NumSalon, e.Tipo_salon, e.capacidad
                  FROM reserva r
                  INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                  WHERE r.Fecha >= ?
                  ORDER BY r.Fecha ASC, r.Hora_Reserva ASC";
$stmtReservas = $mysqli->prepare($queryReservas);
$stmtReservas->bind_param("s", $fechaActual);
$stmtReservas->execute();
$resultReservas = $stmtReservas->get_result();

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

function obtenerNombreAula($espacio) {
    return obtenerNombreEspacio($espacio['Tipo_salon'], $espacio['NumSalon']);
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
include '../front/reservas_html.php';
include '../front/navDOC.php';


// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>