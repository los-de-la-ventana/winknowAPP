<?php

session_start();
require("../conexion.php");
$mysqli = conectarDB();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// Obtener conteos de usuarios
$conteoAdmins = 0;
$conteoDocentes = 0;
$conteoEstudiantes = 0;

// Contar administradores activos
$queryAdmins = "SELECT COUNT(*) as total FROM Administrador a 
                INNER JOIN Usuarios u ON a.Cedula = u.Cedula";
$resultAdmins = $mysqli->query($queryAdmins);
if ($resultAdmins) {
    $rowAdmins = $resultAdmins->fetch_assoc();
    $conteoAdmins = $rowAdmins['total'];
}

// Contar docentes activos
$queryDocentes = "SELECT COUNT(*) as total FROM Docente d 
                  INNER JOIN Usuarios u ON d.Cedula = u.Cedula 
                  WHERE d.Estado = 'Activo' OR d.Estado IS NULL";
$resultDocentes = $mysqli->query($queryDocentes);
if ($resultDocentes) {
    $rowDocentes = $resultDocentes->fetch_assoc();
    $conteoDocentes = $rowDocentes['total'];
}

// Contar estudiantes
$queryEstudiantes = "SELECT COUNT(*) as total FROM Estudiante e 
                     INNER JOIN Usuarios u ON e.Cedula = u.Cedula";
$resultEstudiantes = $mysqli->query($queryEstudiantes);
if ($resultEstudiantes) {
    $rowEstudiantes = $resultEstudiantes->fetch_assoc();
    $conteoEstudiantes = $rowEstudiantes['total'];
}

// Obtener parámetros de filtro
$filtroTipo = isset($_GET['tipo_salon']) ? $_GET['tipo_salon'] : '';
$filtroPiso = isset($_GET['piso']) ? $_GET['piso'] : '';
$filtroCapacidad = isset($_GET['capacidad']) ? $_GET['capacidad'] : '';
$filtroEstado = isset($_GET['estado']) ? $_GET['estado'] : '';

// Construir la consulta de espacios con filtros
$queryEspacios = "SELECT e.*, GROUP_CONCAT(r.nombre_Recurso SEPARATOR ', ') as recursos 
                  FROM Espacios e 
                  LEFT JOIN Recursos r ON e.IdEspacio = r.IdEspacio 
                  WHERE 1=1";

// Agregar condiciones de filtro
if (!empty($filtroTipo)) {
    $queryEspacios .= " AND e.Tipo_salon = '" . $mysqli->real_escape_string($filtroTipo) . "'";
}

// Para el filtro de piso, necesitamos mapear los valores
if (!empty($filtroPiso)) {
    switch($filtroPiso) {
        case 'Planta Baja':
            $queryEspacios .= " AND e.NumEdificio = 0";
            break;
        case 'Primer Piso':
            $queryEspacios .= " AND e.NumEdificio = 1";
            break;
        case 'Segundo Piso':
            $queryEspacios .= " AND e.NumEdificio = 2";
            break;
    }
}

if (!empty($filtroCapacidad)) {
    $queryEspacios .= " AND e.capacidad = " . intval($filtroCapacidad);
}

if (!empty($filtroEstado)) {
    $queryEspacios .= " AND e.Estado_Espacio = '" . $mysqli->real_escape_string($filtroEstado) . "'";
}

$queryEspacios .= " GROUP BY e.IdEspacio ORDER BY e.NumEdificio, e.NumSalon";

$resultEspacios = $mysqli->query($queryEspacios);

// Función para obtener el nombre del piso
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

// Función para obtener el estado con color
function obtenerEstadoConEstilo($estado) {
    switch(strtolower($estado)) {
        case 'disponible':
            return '<span class="estado-disponible">Disponible</span>';
        case 'ocupado':
            return '<span class="estado-ocupado">Ocupado</span>';
        case 'mantenimiento':
            return '<span class="estado-mantenimiento">Mantenimiento</span>';
        default:
            return '<span class="estado-disponible">Disponible</span>';
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
    <title>WinKnow - Gestión de Aulas</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    

</head>
<body>
    <!-- Barra Lateral -->
    <aside class="barra-lateral">
        <div class="logo">
            <div class="icono-logo">WK</div>
            <span>WinKnow</span>
        </div>
        
        <nav class="navegacion">
            <ul>
                <a href="inicio.php"> <li><i class="bi bi-house"></i> Inicio</li></a> 
                <a href="aulas.php"> <li class="activo"><i class="bi bi-building"></i> Aulas</li></a>
                <a href="calendario.php">  <li><i class="bi bi-calendar3"></i> Calendario</li> </a>
                <a href="reportes.php">  <li><i class="bi bi-bar-chart"></i> Reportes</li></a>
                <a href="reportes.php">  <li><i class="bi bi-bar-chart"></i> Administrar usuario</li></a>
                <a href="../login_reg/logout.php"><li><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</li></a>
            </ul>
        </nav>
        
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
    
    <!-- Contenido Principal -->
    <main class="principal">
        <header class="encabezado">
            <h1>Gestión de Aulas y Espacios</h1>
        </header>

        <div class="contenido">
            <!-- Botones de Acción -->
            <div class="acciones">
                <button class="boton-primario"><i class="bi bi-plus"></i> Nueva reserva</button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <section class="estadisticas">
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-person-gear"></i></div>
                    <div>
                        <h3>Administradores Activos</h3>
                        <div class="numero"><?php echo sprintf('%02d', $conteoAdmins); ?></div>
                    </div>
                </div>
                
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-person-workspace"></i></div>
                    <div>
                        <h3>Docentes Activos</h3>
                        <div class="numero"><?php echo sprintf('%02d', $conteoDocentes); ?></div>
                    </div>
                </div>
                
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-people"></i></div>
                    <div>
                        <h3>Estudiantes Activos</h3>
                        <div class="numero"><?php echo sprintf('%02d', $conteoEstudiantes); ?></div>
                    </div>
                </div>
            </section>

            <!-- Filtros -->
            <section class="filtros">
                <div class="pestanas">
                    <div class="pestana activa">Aulas</div>
                </div>  
                
                <div class="controles-filtro">
                    <form method="GET" action="aulas.php">
                        <select name="tipo_salon">
                            <option value="">Tipo de Aula - Todos</option>
                            <option value="Aula Normal" <?php echo ($filtroTipo == 'Aula Normal') ? 'selected' : ''; ?>>Aula Normal</option>
                            <option value="Laboratorio" <?php echo ($filtroTipo == 'Laboratorio') ? 'selected' : ''; ?>>Laboratorio</option>
                            <option value="Sala Especial" <?php echo ($filtroTipo == 'Sala Especial') ? 'selected' : ''; ?>>Sala Especial</option>
                        </select>

                        <select name="piso">
                            <option value="">Piso - Todos</option>
                            <option value="Planta Baja" <?php echo ($filtroPiso == 'Planta Baja') ? 'selected' : ''; ?>>Planta Baja</option>
                            <option value="Primer Piso" <?php echo ($filtroPiso == 'Primer Piso') ? 'selected' : ''; ?>>Primer Piso</option>
                            <option value="Segundo Piso" <?php echo ($filtroPiso == 'Segundo Piso') ? 'selected' : ''; ?>>Segundo Piso</option>
                        </select>

                        <select name="capacidad">
                            <option value="">Capacidad - Cualquiera</option>
                            <option value="25" <?php echo ($filtroCapacidad == '25') ? 'selected' : ''; ?>>25 Estudiantes</option>
                            <option value="30" <?php echo ($filtroCapacidad == '30') ? 'selected' : ''; ?>>30 Estudiantes</option>
                            <option value="40" <?php echo ($filtroCapacidad == '40') ? 'selected' : ''; ?>>40 Estudiantes</option>
                            <option value="50" <?php echo ($filtroCapacidad == '50') ? 'selected' : ''; ?>>50 Personas</option>
                        </select>

                        <select name="estado">
                            <option value="">Estado - Todos</option>
                            <option value="Disponible" <?php echo ($filtroEstado == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                            <option value="Ocupado" <?php echo ($filtroEstado == 'Ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                            <option value="Mantenimiento" <?php echo ($filtroEstado == 'Mantenimiento') ? 'selected' : ''; ?>>Mantenimiento</option>
                        </select>

                        <button type="button" onclick="window.location.href='aulas.php'" class="boton-secundario">Limpiar</button>
                        <button type="submit" class="boton-primario">Aplicar Filtro</button>
                    </form>
                </div>

                <?php if (!empty($filtroTipo) || !empty($filtroPiso) || !empty($filtroCapacidad) || !empty($filtroEstado)): ?>
                <div class="filtros-activos">
                    <strong>Filtros aplicados:</strong>
                    <?php if (!empty($filtroTipo)): ?><span class="filtro-tag">Tipo: <?php echo htmlspecialchars($filtroTipo); ?></span><?php endif; ?>
                    <?php if (!empty($filtroPiso)): ?><span class="filtro-tag">Piso: <?php echo htmlspecialchars($filtroPiso); ?></span><?php endif; ?>
                    <?php if (!empty($filtroCapacidad)): ?><span class="filtro-tag">Capacidad: <?php echo htmlspecialchars($filtroCapacidad); ?></span><?php endif; ?>
                    <?php if (!empty($filtroEstado)): ?><span class="filtro-tag">Estado: <?php echo htmlspecialchars($filtroEstado); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
            </section>

            <!-- Grilla de Aulas -->
            <section class="aulas">
                <div class="aulas-header">
                    <h2>Aulas y Espacios</h2>
                    <?php if ($resultEspacios): ?>
                        <p>Mostrando <?php echo $resultEspacios->num_rows; ?> resultado(s)</p>
                    <?php endif; ?>
                </div>
                
                <div class="grilla">
                    <?php 
                    if ($resultEspacios && $resultEspacios->num_rows > 0):
                        while ($espacio = $resultEspacios->fetch_assoc()): 
                    ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <?php 
                                    if ($espacio['Tipo_salon'] == 'Laboratorio') {
                                        echo 'Laboratorio ' . $espacio['NumSalon'];
                                    } elseif ($espacio['Tipo_salon'] == 'Sala Especial') {
                                        echo 'Sala Especial ' . $espacio['NumSalon'];
                                    } else {
                                        echo 'Aula ' . $espacio['NumSalon'];
                                    }
                                    ?>
                                </h4>
                                <div class="detalles">
                                    <?php echo obtenerNombrePiso($espacio['NumEdificio']); ?><br>
                                    Capacidad: <?php echo $espacio['capacidad']; ?> Personas<br>
                                    <?php if (!empty($espacio['recursos'])): ?>
                                        <?php echo htmlspecialchars($espacio['recursos']); ?>
                                    <?php else: ?>
                                        Sin recursos especificados
                                    <?php endif; ?>
                                </div>
                                <div class="etiqueta"><?php echo htmlspecialchars($espacio['Tipo_salon']); ?></div>
                                <div class="estado-aula">
                                    <?php echo obtenerEstadoConEstilo($espacio['Estado_Espacio']); ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <div class="sin-resultados">
                            <p>No se encontraron aulas que coincidan con los filtros aplicados.</p>
                            <button onclick="window.location.href='aulas.php'" class="boton-secundario">Ver todas las aulas</button>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <?php 
    // Cerrar la conexión
    if ($mysqli) {
        $mysqli->close();
    }
    ?>
</body>
</html>