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
// PROCESAR CREACIÓN DE RESERVA DE RECURSO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_reserva_recurso'])) {
    $idRecurso = intval($_POST['id_recurso']);
    $fecha = $_POST['fecha'];
    $horaReserva = intval($_POST['hora_reserva']);
    
    // Validar que la fecha no sea pasada
    $fechaActual = date('Y-m-d');
    if ($fecha < $fechaActual) {
        $_SESSION['mensaje'] = "No se puede reservar una fecha pasada.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        // NUEVA VALIDACIÓN: Verificar si ya existe UNA reserva (pendiente o aprobada) para ese recurso, fecha y hora
        $sqlCheckDuplicado = "SELECT COUNT(*) as total FROM reserva_recurso 
                             WHERE IdRecurso = ? AND Fecha = ? AND Hora_Reserva = ?";
        $stmtCheckDuplicado = $mysqli->prepare($sqlCheckDuplicado);
        $stmtCheckDuplicado->bind_param("isi", $idRecurso, $fecha, $horaReserva);
        $stmtCheckDuplicado->execute();
        $resultCheckDuplicado = $stmtCheckDuplicado->get_result();
        $rowDuplicado = $resultCheckDuplicado->fetch_assoc();
        $stmtCheckDuplicado->close();
        
        if ($rowDuplicado['total'] > 0) {
            $_SESSION['mensaje'] = "Ya existe una reserva para este recurso en la misma fecha y hora.";
            $_SESSION['tipo_mensaje'] = "error_duplicado";
        } else {
            // Insertar la reserva con estado pendiente (aprobada = 0)
            $sqlInsert = "INSERT INTO reserva_recurso (IdRecurso, Fecha, Hora_Reserva, aprobada) VALUES (?, ?, ?, 0)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $stmtInsert->bind_param("isi", $idRecurso, $fecha, $horaReserva);
            
            if ($stmtInsert->execute()) {
                $_SESSION['mensaje'] = "Reserva de recurso creada exitosamente. Pendiente de aprobación.";
                $_SESSION['tipo_mensaje'] = "exito";
            } else {
                $_SESSION['mensaje'] = "Error al crear la reserva de recurso: " . $mysqli->error;
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmtInsert->close();
        }
    }
    
    header("Location: docente_reservas_recursos.php");
    exit;
}

// ============================================
// PROCESAR ELIMINACIÓN DE RESERVA DE RECURSO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_reserva_recurso'])) {
    $idReservaRecurso = intval($_POST['id_reserva_recurso']);
    
    $sqlDelete = "DELETE FROM reserva_recurso WHERE IdReservaRecurso = ?";
    $stmtDelete = $mysqli->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idReservaRecurso);
    
    if ($stmtDelete->execute()) {
        $_SESSION['mensaje'] = "Reserva de recurso eliminada exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la reserva de recurso: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtDelete->close();
    
    header("Location: docente_reservas_recursos.php");
    exit;
}

// ============================================
// OBTENER DATOS - RECURSOS
// ============================================

// Obtener todos los recursos con información del espacio donde están
$queryRecursos = "SELECT r.IdRecurso, r.nombre_Recurso, 
                         e.NumSalon, e.Tipo_salon
                  FROM recursos r
                  INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                  ORDER BY r.nombre_Recurso";
$resultRecursos = $mysqli->query($queryRecursos);

// ============================================
// OBTENER RESERVAS DE RECURSOS
// ============================================
$fechaActual = date('Y-m-d');

// RESERVAS DE RECURSOS PENDIENTES (aprobada = 0)
$queryReservasPendientes = "SELECT rr.IdReservaRecurso, rr.Fecha, rr.Hora_Reserva, 
                                    r.nombre_Recurso, r.IdRecurso,
                                    e.NumSalon, e.Tipo_salon
                             FROM reserva_recurso rr
                             INNER JOIN recursos r ON rr.IdRecurso = r.IdRecurso
                             INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                             WHERE rr.Fecha >= ? AND rr.aprobada = 0
                             ORDER BY rr.Fecha ASC, rr.Hora_Reserva ASC";
$stmtReservasPendientes = $mysqli->prepare($queryReservasPendientes);
$stmtReservasPendientes->bind_param("s", $fechaActual);
$stmtReservasPendientes->execute();
$resultReservasPendientes = $stmtReservasPendientes->get_result();

// RESERVAS DE RECURSOS APROBADAS (aprobada = 1)
$queryReservasAprobadas = "SELECT rr.IdReservaRecurso, rr.Fecha, rr.Hora_Reserva, 
                                   r.nombre_Recurso, r.IdRecurso,
                                   e.NumSalon, e.Tipo_salon
                            FROM reserva_recurso rr
                            INNER JOIN recursos r ON rr.IdRecurso = r.IdRecurso
                            INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                            WHERE rr.Fecha >= ? AND rr.aprobada = 1
                            ORDER BY rr.Fecha ASC, rr.Hora_Reserva ASC";
$stmtReservasAprobadas = $mysqli->prepare($queryReservasAprobadas);
$stmtReservasAprobadas->bind_param("s", $fechaActual);
$stmtReservasAprobadas->execute();
$resultReservasAprobadas = $stmtReservasAprobadas->get_result();

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

function obtenerIconoRecurso($nombreRecurso) {
    $nombre = strtolower($nombreRecurso);
    
    if (strpos($nombre, 'proyector') !== false) return 'bi-projector';
    if (strpos($nombre, 'computadora') !== false || strpos($nombre, 'pc') !== false) return 'bi-pc-display';
    if (strpos($nombre, 'pizarra') !== false) return 'bi-easel';
    if (strpos($nombre, 'micrófono') !== false || strpos($nombre, 'microfono') !== false) return 'bi-mic';
    if (strpos($nombre, 'parlante') !== false || strpos($nombre, 'altavoz') !== false) return 'bi-speaker';
    if (strpos($nombre, 'cámara') !== false || strpos($nombre, 'camara') !== false) return 'bi-camera-video';
    if (strpos($nombre, 'televisor') !== false || strpos($nombre, 'tv') !== false) return 'bi-tv';
    if (strpos($nombre, 'tablet') !== false) return 'bi-tablet';
    if (strpos($nombre, 'impresora') !== false) return 'bi-printer';
    
    return 'bi-box-seam';
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
include '../front/reservas_recursos_html.php';
include '../front/navdoc.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>