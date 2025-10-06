<?php
// INICIO DE SESIÓN Y CONFIGURACIÓN
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// MANEJO DE ELIMINACIÓN DE AULA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_aula'])) {
    $numSalon = intval($_POST['num_salon']);
    
    // Primero verificar si hay reservas asociadas
    $sqlCheck = "SELECT COUNT(*) as total FROM Reserva r 
                 INNER JOIN Espacios e ON r.IdEspacio = e.IdEspacio 
                 WHERE e.NumSalon = ?";
    $stmtCheck = $mysqli->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $numSalon);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $row = $resultCheck->fetch_assoc();
    
    if ($row['total'] > 0) {
        $_SESSION['mensaje'] = "No se puede eliminar el aula porque tiene reservas asociadas.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        // Eliminar el espacio
        $sql = "DELETE FROM Espacios WHERE NumSalon = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $numSalon);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Aula eliminada exitosamente.";
            $_SESSION['tipo_mensaje'] = "exito";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el aula: " . $mysqli->error;
            $_SESSION['tipo_mensaje'] = "error";
        }
        $stmt->close();
    }
    $stmtCheck->close();
    
    // Redirigir para evitar reenvío de formulario
    header("Location: aulas.php");
    exit;
}

// SEGURIDAD: VERIFICAR PERMISOS DE DOCENTE     
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'docente') {
    header("Location: ../login_reg/login.php");
    exit;
}

// ============================================
// FILTROS DE BÚSQUEDA
// ============================================
$filtroTipo      = $_GET['tipo_salon'] ?? '';
$filtroCapacidad = $_GET['capacidad'] ?? '';

// Construir consulta dinámica
$queryEspacios = "SELECT * FROM Espacios WHERE 1=1";

// Filtro tipo salón
if (!empty($filtroTipo)) {
    $queryEspacios .= " AND Tipo_salon = '" . $mysqli->real_escape_string($filtroTipo) . "'";
}

// Filtro capacidad
if (!empty($filtroCapacidad)) {
    $queryEspacios .= " AND capacidad = " . intval($filtroCapacidad);
}

// Ordenamiento
$queryEspacios .= " ORDER BY NumSalon";
$resultEspacios = $mysqli->query($queryEspacios);

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================
function obtenerNombreAula($espacio) {
    return match ($espacio['Tipo_salon']) {
        'Taller' => 'Taller ' . $espacio['NumSalon'],
        'Salon'  => 'Salon ' . $espacio['NumSalon'],
        'Laboratorio' => 'Laboratorio ' . $espacio['NumSalon'],
        default  => 'Aula ' . $espacio['NumSalon']
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

// ============================================
// INCLUIR HEADER y PHP AULAS
// ============================================
include '../front/header.html';
include '../front/reservas_html.php';

?>
