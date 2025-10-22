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
// PROCESAR APROBACIÓN DE RESERVA DE RECURSO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar_reserva'])) {
    $idReservaRecurso = intval($_POST['id_reserva_recurso']);
    
    // Actualizar el campo aprobada a 1 (true)
    $sqlAprobar = "UPDATE reserva_recurso SET aprobada = 1 WHERE IdReservaRecurso = ?";
    $stmtAprobar = $mysqli->prepare($sqlAprobar);
    $stmtAprobar->bind_param("i", $idReservaRecurso);
    
    if ($stmtAprobar->execute()) {
        $_SESSION['mensaje'] = "Reserva de recurso aprobada exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al aprobar la reserva: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtAprobar->close();
    
    header("Location: administrar_reservas_recursos.php");
    exit;
}

// ============================================
// PROCESAR RECHAZO DE RESERVA DE RECURSO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechazar_reserva'])) {
    $idReservaRecurso = intval($_POST['id_reserva_recurso']);
    
    $sqlDelete = "DELETE FROM reserva_recurso WHERE IdReservaRecurso = ?";
    $stmtDelete = $mysqli->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idReservaRecurso);
    
    if ($stmtDelete->execute()) {
        $_SESSION['mensaje'] = "Reserva de recurso rechazada y eliminada exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al rechazar la reserva: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtDelete->close();
    
    header("Location: administrar_reservas_recursos.php");
    exit;
}

// ============================================
// OBTENER SOLO RESERVAS PENDIENTES (aprobada = 0)
// Esta función PHP realiza una consulta a la base de datos para
//obtener reservas pendientes de aprobación. Te la explico paso a paso:
// ============================================
$fechaActual = date('Y-m-d');
$queryReservas = "SELECT rr.IdReservaRecurso, rr.Fecha, rr.Hora_Reserva, 
                         r.nombre_Recurso, r.IdRecurso,
                         e.NumSalon, e.Tipo_salon
                  FROM reserva_recurso rr
                  INNER JOIN recursos r ON rr.IdRecurso = r.IdRecurso
                  INNER JOIN espacios e ON r.IdEspacio = e.IdEspacio
                  WHERE rr.Fecha >= ? AND rr.aprobada = 0
                  ORDER BY rr.Fecha ASC, rr.Hora_Reserva ASC";
$stmtReservas = $mysqli->prepare($queryReservas);
$stmtReservas->bind_param("s", $fechaActual);
$stmtReservas->execute();
$resultReservas = $stmtReservas->get_result();

// ============================================
// ESTADÍSTICAS
// ============================================
$sqlStats = "SELECT COUNT(*) as total FROM reserva_recurso WHERE Fecha >= ? AND aprobada = 0";
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
include '../front/admin_reservasrec_html.php';
include '../front/navadm.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>