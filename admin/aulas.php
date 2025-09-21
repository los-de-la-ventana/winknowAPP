<?php
// Iniciar la sesion del usuario
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// SECCION DE SEGURIDAD - Verificar permisos de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// SECCION DE CONTEO DE USUARIOS - Obtener estadisticas generales
$conteoAdmins = 0;
$conteoDocentes = 0;
$conteoEstudiantes = 0;

// Contar administradores activos en el sistema
$queryAdmins = "SELECT COUNT(*) as total FROM Administrador a 
                INNER JOIN Usuarios u ON a.Cedula = u.Cedula";
$resultAdmins = $mysqli->query($queryAdmins);
if ($resultAdmins) {
    $rowAdmins = $resultAdmins->fetch_assoc();
    $conteoAdmins = $rowAdmins['total'];
}

// Contar docentes activos (incluye los que no tienen estado definido)
$queryDocentes = "SELECT COUNT(*) as total FROM Docente d 
                  INNER JOIN Usuarios u ON d.Cedula = u.Cedula 
                  WHERE d.Estado = 'Activo' OR d.Estado IS NULL";
$resultDocentes = $mysqli->query($queryDocentes);
if ($resultDocentes) {
    $rowDocentes = $resultDocentes->fetch_assoc();
    $conteoDocentes = $rowDocentes['total'];
}

// Contar estudiantes registrados en el sistema
$queryEstudiantes = "SELECT COUNT(*) as total FROM Estudiante e 
                     INNER JOIN Usuarios u ON e.Cedula = u.Cedula";
$resultEstudiantes = $mysqli->query($queryEstudiantes);
if ($resultEstudiantes) {
    $rowEstudiantes = $resultEstudiantes->fetch_assoc();
    $conteoEstudiantes = $rowEstudiantes['total'];
}

// SECCION DE FILTROS - Capturar parametros de busqueda desde el formulario
$filtroTipo = isset($_GET['tipo_salon']) ? trim($_GET['tipo_salon']) : '';
$filtroPiso = isset($_GET['piso']) ? trim($_GET['piso']) : '';
$filtroCapacidad = isset($_GET['capacidad']) ? trim($_GET['capacidad']) : '';

// SECCION DE CONSULTA PRINCIPAL - Construir consulta dinamica con filtros
// Esta consulta obtiene espacios sin recursos ya que se elimino esa funcionalidad
$queryEspacios = "SELECT * FROM Espacios WHERE 1=1";

// Aplicar filtro por tipo de salon si se selecciono
if (!empty($filtroTipo)) {
    $queryEspacios .= " AND Tipo_salon = '" . $mysqli->real_escape_string($filtroTipo) . "'";
}

// Aplicar filtro por piso - mapear texto a numero de edificio
if (!empty($filtroPiso)) {
    switch($filtroPiso) {
        case 'Planta Baja':
            $queryEspacios .= " AND NumEdificio = 0";
            break;
        case 'Primer Piso':
            $queryEspacios .= " AND NumEdificio = 1";
            break;
        case 'Segundo Piso':
            $queryEspacios .= " AND NumEdificio = 2";
            break;
    }
}

// Aplicar filtro por capacidad exacta
if (!empty($filtroCapacidad)) {
    $queryEspacios .= " AND capacidad = " . intval($filtroCapacidad);
}

// Ordenar por edificio y numero de salon
$queryEspacios .= " ORDER BY NumEdificio, NumSalon";

// Ejecutar la consulta de espacios
$resultEspacios = $mysqli->query($queryEspacios);

// FUNCIONES DE UTILIDAD

/**
 * Convierte el numero de edificio a nombre legible del piso
 * @param int $numEdificio Numero del edificio (0, 1, 2, etc.)
 * @return string Nombre del piso
 */
function obtenerNombrePiso($numEdificio) {
    switch($numEdificio) {
        case 0:
            return 'Planta Baja';
        case 1:
            return 'Primer Piso';
        case 2:
            return 'Segundo Piso';
        default:
            return 'Piso ' . $numEdificio;
    }
}

/**
 * Genera el nombre del aula basado en su tipo y numero
 * @param array $espacio Array con datos del espacio
 * @return string Nombre formateado del aula
 */
function obtenerNombreAula($espacio) {
    switch($espacio['Tipo_salon']) {
        case 'Taller':
            return 'Taller ' . $espacio['NumSalon'];
        case 'Salon':
            return 'Salon ' . $espacio['NumSalon'];
        default:
            return 'Aula ' . $espacio['NumSalon'];
    }
}

/**
 * Obtiene el icono apropiado para cada tipo de espacio
 * @param string $tipoSalon Tipo del salon
 * @return string Clase de icono de Bootstrap
 */
function obtenerIconoTipo($tipoSalon) {
    switch($tipoSalon) {
        case 'Taller':
            return 'bi-tools';
        case 'Salon':
            return 'bi-building';
        default:
            return 'bi-door-open';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/wk_logo.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../inicio.css">
    <link rel="stylesheet" href="aulas.css">
    <title>WinKnow - Gestion de Aulas</title>

    <!-- Bootstrap Icons para iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts para tipografia -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- BARRA LATERAL DE NAVEGACION -->
    <aside class="barra-lateral">
        <div class="logo">
            <div class="icono-logo">WK</div>
            <span>WinKnow</span>
        </div>
        
        <!-- Menu de navegacion principal -->
        <nav class="navegacion">
            <ul>
                <a href="inicio.php"> <li><i class="bi bi-house"></i> Inicio</li></a> 
                <a href="aulas.php"> <li class="activo"><i class="bi bi-building"></i> Aulas</li></a>
                <a href="calendario.php">  <li><i class="bi bi-calendar3"></i> Calendario</li> </a>
                <a href="reportes.php">  <li><i class="bi bi-bar-chart"></i> Reportes</li></a>
                <a href="usuarios.php">  <li><i class="bi bi-people"></i> Administrar Usuarios</li></a>
                <a href="../login_reg/logout.php"><li><i class="bi bi-box-arrow-right"></i> Cerrar Sesion</li></a>
            </ul>
        </nav>
        
        <!-- Informacion del usuario logueado -->
        <div class="usuario">
            <div class="info-usuario">
                <div class="nombre-usuario">
                    <i class="bi bi-person-circle"></i>
                    <?php   
                        if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
                            echo htmlspecialchars($_SESSION['nombre']);
                        } else {
                            echo '<span class="usuario-blanco">Usuario</span>';
                        }
                    ?>
                </div>
                <div class="rol-usuario">
                    Administrador
                    <?php if (isset($_SESSION['rolAdmin']) && !empty($_SESSION['rolAdmin'])): ?>
                        - <?php echo htmlspecialchars($_SESSION['rolAdmin']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- CONTENIDO PRINCIPAL DE LA PAGINA -->
    <main class="principal">
        <header class="encabezado">
            <h1>Gestion de Aulas y Espacios</h1>
        </header>

        <div class="contenido">


            <!-- TARJETAS DE ESTADISTICAS DE USUARIOS -->
            <section class="estadisticas">
                <!-- Contador de administradores -->
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-person-gear"></i></div>
                    <div>
                        <h3>Administradores</h3>
                        <div class="numero"><?php echo sprintf('%02d', $conteoAdmins); ?></div>
                    </div>
                </div>
                
                <!-- Contador de docentes -->
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-person-workspace"></i></div>
                    <div>
                        <h3>Docentes Activos</h3>
                        <div class="numero"><?php echo sprintf('%02d', $conteoDocentes); ?></div>
                    </div>
                </div>
                
                <!-- Contador de estudiantes -->
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-people"></i></div>
                    <div>
                        <h3>Estudiantes</h3>
                        <div class="numero"><?php echo sprintf('%02d', $conteoEstudiantes); ?></div>
                    </div>
                </div>
            </section>

            <!-- SECCION DE FILTROS DE BUSQUEDA - Simplificados -->
            <section class="filtros">
                <h2>Filtrar Espacios</h2> <br />
                
                <!-- Formulario de filtros -->
                <div class="controles-filtro">
                    <form method="GET" action="aulas.php">
                        <!-- Filtro por tipo de salon - Solo 3 opciones -->
                        <select name="tipo_salon">
                            <option value="">Tipo de Espacio - Todos</option>
                            <option value="Salon" <?php echo ($filtroTipo == 'Salon') ? 'selected' : ''; ?>>Salon</option>
                            <option value="Aula" <?php echo ($filtroTipo == 'Aula') ? 'selected' : ''; ?>>Aula</option>
                            <option value="Taller" <?php echo ($filtroTipo == 'Taller') ? 'selected' : ''; ?>>Taller</option>
                        </select>

                        <!-- Filtro por piso del edificio -->
                        <select name="piso">
                            <option value="">Piso - Todos</option>
                            <option value="Planta Baja" <?php echo ($filtroPiso == 'Planta Baja') ? 'selected' : ''; ?>>Planta Baja</option>
                            <option value="Primer Piso" <?php echo ($filtroPiso == 'Primer Piso') ? 'selected' : ''; ?>>Primer Piso</option>
                            <option value="Segundo Piso" <?php echo ($filtroPiso == 'Segundo Piso') ? 'selected' : ''; ?>>Segundo Piso</option>
                        </select>

                        <!-- Filtro por capacidad de personas -->
                        <select name="capacidad">
                            <option value="">Capacidad - Cualquiera</option>
                            <option value="30" <?php echo ($filtroCapacidad == '30') ? 'selected' : ''; ?>>30 Personas</option>
                            <option value="40" <?php echo ($filtroCapacidad == '40') ? 'selected' : ''; ?>>40 Personas</option>
                            <option value="60" <?php echo ($filtroCapacidad == '60') ? 'selected' : ''; ?>>60 Personas</option>
                            <option value="80" <?php echo ($filtroCapacidad == '80') ? 'selected' : ''; ?>>80 Personas</option>
                        </select>

                        <br />      <br>
                        <!-- Botones de accion para filtros -->
                        <button type="submit" class="boton-primario">
                            <i class="bi bi-funnel"></i> Aplicar Filtros
                        </button>

                    </form>
                </div>

              
            </section>

            <!-- SECCION DE RESULTADOS - GRILLA DE AULAS SIMPLIFICADA -->
            <section class="aulas">
                <div class="aulas-header">
                    <h2><i class="bi bi-building"></i> Espacios Disponibles</h2>
                    <!-- Mostrar cantidad de resultados encontrados -->
                    <?php if ($resultEspacios): ?>
                        <p><strong>Mostrando <?php echo $resultEspacios->num_rows; ?> resultado(s)</strong></p>
                    <?php endif; ?>
                </div>
                
                <!-- Grilla que contiene las tarjetas de cada aula -->
                <div class="grilla">
                    <?php 
                    // Verificar si hay resultados para mostrar
                    if ($resultEspacios && $resultEspacios->num_rows > 0):
                        // Iterar sobre cada espacio encontrado
                        while ($espacio = $resultEspacios->fetch_assoc()): 
                    ?>
                        <!-- Tarjeta individual de cada aula - Simplificada -->
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?php echo obtenerIconoTipo($espacio['Tipo_salon']); ?>"></i>
                                    <?php echo obtenerNombreAula($espacio); ?>
                                </h4>
                                <!-- Detalles del espacio - Simplificados -->
                                <div class="detalles">
                                    <strong><i class="bi bi-geo-alt"></i> <?php echo obtenerNombrePiso($espacio['NumEdificio']); ?></strong><br>
                                    <i class="bi bi-people"></i> Capacidad: <strong><?php echo $espacio['capacidad']; ?></strong> Personas
                                </div>
                                
                                <!-- Etiqueta de tipo de salon con colores -->
                                <div class="tipo-salon <?php echo strtolower($espacio['Tipo_salon']); ?>">
                                    <i class="bi bi-tag"></i> <?php echo htmlspecialchars($espacio['Tipo_salon']); ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile;

                    ?>
                        
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <?php 
    // LIMPIEZA - Cerrar la conexion a la base de datos
    if ($mysqli) {
        $mysqli->close();
    }
    ?>
</body>
</html>