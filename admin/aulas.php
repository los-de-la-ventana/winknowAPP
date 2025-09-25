<?php
// INICIO DE SESIÓN Y CONFIGURACIÓN
session_start();
require("../conexion.php");

$mysqli = conectarDB();

// SEGURIDAD: VERIFICAR PERMISOS DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// ESTADÍSTICAS DE USUARIOS
$conteoAdmins     = 0;
$conteoDocentes   = 0;
$conteoEstudiantes = 0;

// Contar administradores
$sql = "SELECT COUNT(*) as total FROM Administrador a 
        INNER JOIN Usuarios u ON a.Cedula = u.Cedula";
if ($res = $mysqli->query($sql)) {
    $conteoAdmins = $res->fetch_assoc()['total'];
}

// Contar docentes activos
$sql = "SELECT COUNT(*) as total FROM Docente d 
        INNER JOIN Usuarios u ON d.Cedula = u.Cedula 
        WHERE d.Estado = 'Activo' OR d.Estado IS NULL";
if ($res = $mysqli->query($sql)) {
    $conteoDocentes = $res->fetch_assoc()['total'];
}

// Contar estudiantes
$sql = "SELECT COUNT(*) as total FROM Estudiante e 
        INNER JOIN Usuarios u ON e.Cedula = u.Cedula";
if ($res = $mysqli->query($sql)) {
    $conteoEstudiantes = $res->fetch_assoc()['total'];
}

// ============================================
// FILTROS DE BÚSQUEDA
// ============================================
$filtroTipo      = $_GET['tipo_salon'] ?? '';
$filtroPiso      = $_GET['piso'] ?? '';
$filtroCapacidad = $_GET['capacidad'] ?? '';

// Construir consulta dinámica
$queryEspacios = "SELECT * FROM Espacios WHERE 1=1";

// Filtro tipo salón
if (!empty($filtroTipo)) {
    $queryEspacios .= " AND Tipo_salon = '" . $mysqli->real_escape_string($filtroTipo) . "'";
}

// Filtro piso
if (!empty($filtroPiso)) {
    $mapaPisos = [
        'Planta Baja' => 0,
        'Primer Piso' => 1,
        'Segundo Piso' => 2
    ];
    if (isset($mapaPisos[$filtroPiso])) {
        $queryEspacios .= " AND NumEdificio = " . $mapaPisos[$filtroPiso];
    }
}

// Filtro capacidad
if (!empty($filtroCapacidad)) {
    $queryEspacios .= " AND capacidad = " . intval($filtroCapacidad);
}

// Ordenamiento
$queryEspacios .= " ORDER BY NumEdificio, NumSalon";
$resultEspacios = $mysqli->query($queryEspacios);

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================
function obtenerNombrePiso($numEdificio) {
    return match ($numEdificio) {
        0 => 'Planta Baja',
        1 => 'Primer Piso',
        2 => 'Segundo Piso',
        default => 'Piso ' . $numEdificio
    };
}

function obtenerNombreAula($espacio) {
    return match ($espacio['Tipo_salon']) {
        'Taller' => 'Taller ' . $espacio['NumSalon'],
        'Salon'  => 'Salon ' . $espacio['NumSalon'],
        default  => 'Aula ' . $espacio['NumSalon']
    };
}

function obtenerIconoTipo($tipoSalon) {
    return match ($tipoSalon) {
        'Taller' => 'bi-tools',
        'Salon'  => 'bi-building',
        default  => 'bi-door-open'
    };
}

// ============================================
// INCLUIR HEADER
// ============================================
include '../headerfooter/header.html';
include '../headerfooter/navADM.php';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WinKnow - Gestión de Aulas</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>


<!-- ==================== CONTENIDO PRINCIPAL ==================== -->
<main class="principal">
    <div class="contenido">

        <!-- TARJETAS DE ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-person-gear"></i></div>
                <div>
                    <h3>Administradores</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoAdmins); ?></div>
                </div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3>Docentes Activos</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoDocentes); ?></div>
                </div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-people"></i></div>
                <div>
                    <h3>Estudiantes</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoEstudiantes); ?></div>
                </div>
            </div>
        </section>

        <!-- FILTROS DE BÚSQUEDA -->
        <section class="filtros">
            <h2>Filtrar Espacios</h2><br>
            <form method="GET" action="aulas.php" class="controles-filtro">
                <select name="tipo_salon">
                    <option value="">Tipo de Espacio - Todos</option>
                    <option value="Salon"  <?= $filtroTipo == 'Salon' ? 'selected' : ''; ?>>Salon</option>
                    <option value="Aula"   <?= $filtroTipo == 'Aula' ? 'selected' : ''; ?>>Aula</option>
                    <option value="Taller" <?= $filtroTipo == 'Taller' ? 'selected' : ''; ?>>Taller</option>
                </select>
                <select name="piso">
                    <option value="">Piso - Todos</option>
                    <option value="Planta Baja" <?= $filtroPiso == 'Planta Baja' ? 'selected' : ''; ?>>Planta Baja</option>
                    <option value="Primer Piso" <?= $filtroPiso == 'Primer Piso' ? 'selected' : ''; ?>>Primer Piso</option>
                    <option value="Segundo Piso" <?= $filtroPiso == 'Segundo Piso' ? 'selected' : ''; ?>>Segundo Piso</option>
                </select>
                <select name="capacidad">
                    <option value="">Capacidad - Cualquiera</option>
                    <option value="30" <?= $filtroCapacidad == '30' ? 'selected' : ''; ?>>30 Personas</option>
                    <option value="40" <?= $filtroCapacidad == '40' ? 'selected' : ''; ?>>40 Personas</option>
                    <option value="60" <?= $filtroCapacidad == '60' ? 'selected' : ''; ?>>60 Personas</option>
                    <option value="80" <?= $filtroCapacidad == '80' ? 'selected' : ''; ?>>80 Personas</option>
                </select>
                <br><br>
                <button type="submit" class="boton-primario">
                    <i class="bi bi-funnel"></i> Aplicar Filtros
                </button>
            </form>
        </section>

        <!-- RESULTADOS DE AULAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-building"></i> Espacios Disponibles</h2>
                <?php if ($resultEspacios): ?>
                    <p><strong>Mostrando <?= $resultEspacios->num_rows; ?> resultado(s)</strong></p>
                <?php endif; ?>
            </div>

            <div class="grilla">
                <?php if ($resultEspacios && $resultEspacios->num_rows > 0): ?>
                    <?php while ($espacio = $resultEspacios->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoTipo($espacio['Tipo_salon']); ?>"></i>
                                    <?= obtenerNombreAula($espacio); ?>
                                </h4>
                                <div class="detalles">
                                    <strong><i class="bi bi-geo-alt"></i> <?= obtenerNombrePiso($espacio['NumEdificio']); ?></strong><br>
                                    <i class="bi bi-people"></i> Capacidad: <strong><?= $espacio['capacidad']; ?></strong> Personas
                                </div>
                                <div class="tipo-salon <?= strtolower($espacio['Tipo_salon']); ?>">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($espacio['Tipo_salon']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php
// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>

</body>
</html>
