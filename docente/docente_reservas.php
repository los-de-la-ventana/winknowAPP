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
        // NUEVA VALIDACIÓN: Verificar si ya existe UNA reserva (pendiente o aprobada) para ese espacio, fecha y hora
        $sqlCheckDuplicado = "SELECT COUNT(*) as total FROM reserva 
                             WHERE IdEspacio = ? AND Fecha = ? AND Hora_Reserva = ?";
        $stmtCheckDuplicado = $mysqli->prepare($sqlCheckDuplicado);
        $stmtCheckDuplicado->bind_param("isi", $idEspacio, $fecha, $horaReserva);
        $stmtCheckDuplicado->execute();
        $resultCheckDuplicado = $stmtCheckDuplicado->get_result();
        $rowDuplicado = $resultCheckDuplicado->fetch_assoc();
        $stmtCheckDuplicado->close();
        
        if ($rowDuplicado['total'] > 0) {
            $_SESSION['mensaje'] = "Ya existe una reserva para este espacio en la misma fecha y hora.";
            $_SESSION['tipo_mensaje'] = "error_duplicado";
        } else {
            // Insertar la reserva con estado pendiente (aprobada = 0)
            $sqlInsert = "INSERT INTO reserva (IdEspacio, Fecha, Hora_Reserva, aprobada) VALUES (?, ?, ?, 0)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $stmtInsert->bind_param("isi", $idEspacio, $fecha, $horaReserva);
            
            if ($stmtInsert->execute()) {
                $_SESSION['mensaje'] = "Reserva creada exitosamente. Pendiente de aprobación.";
                $_SESSION['tipo_mensaje'] = "exito";
            } else {
                $_SESSION['mensaje'] = "Error al crear la reserva: " . $mysqli->error;
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmtInsert->close();
        }
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
// OBTENER RESERVAS PENDIENTES (aprobada = 0)
// ============================================
$fechaActual = date('Y-m-d');
$queryReservasPendientes = "SELECT r.IdReserva, r.Fecha, r.Hora_Reserva, 
                                    e.NumSalon, e.Tipo_salon, e.capacidad
                             FROM reserva r
                             INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                             WHERE r.Fecha >= ? AND r.aprobada = 0
                             ORDER BY r.Fecha ASC, r.Hora_Reserva ASC";
$stmtReservasPendientes = $mysqli->prepare($queryReservasPendientes);
$stmtReservasPendientes->bind_param("s", $fechaActual);
$stmtReservasPendientes->execute();
$resultReservasPendientes = $stmtReservasPendientes->get_result();

// ============================================
// OBTENER RESERVAS APROBADAS (aprobada = 1)
// ============================================
$queryReservasAprobadas = "SELECT r.IdReserva, r.Fecha, r.Hora_Reserva, 
                                   e.NumSalon, e.Tipo_salon, e.capacidad
                            FROM reserva r
                            INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                            WHERE r.Fecha >= ? AND r.aprobada = 1
                            ORDER BY r.Fecha ASC, r.Hora_Reserva ASC";
$stmtReservasAprobadas = $mysqli->prepare($queryReservasAprobadas);
$stmtReservasAprobadas->bind_param("s", $fechaActual);
$stmtReservasAprobadas->execute();
$resultReservasAprobadas = $stmtReservasAprobadas->get_result();

// ============================================
// FUNCIÓN PARA VERIFICAR SI UN SLOT ESTÁ OCUPADO
// ============================================
function verificarSlotOcupado($mysqli, $idEspacio, $fecha, $hora) {
    $sql = "SELECT COUNT(*) as total FROM reserva 
            WHERE IdEspacio = ? AND Fecha = ? AND Hora_Reserva = ? AND aprobada = 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("isi", $idEspacio, $fecha, $hora);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total'] > 0;
}

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
include '../front/navdoc.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>