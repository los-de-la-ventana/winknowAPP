<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/image-removebg-preview.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../inicio.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinKnow - Panel Administrador</title>

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
                <a href="inicio.php"><li class="activo"><i class="bi bi-house"></i> Inicio</li></a> 
                <a href="aulas.php"><li><i class="bi bi-building"></i> Aulas</li></a>
                <a href="calendario.php"><li><i class="bi bi-calendar3"></i> Calendario</li></a>
                <a href="reportes.php"><li><i class="bi bi-bar-chart"></i> Reportes</li></a>
                <a href="usuarios.php"><li><i class="bi bi-people"></i> Administrar Usuarios</li></a>
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
            <h1>
                Hola, <?php echo isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '<span class="usuario-blanco">Usuario</span>'; ?>
            </h1>
        </header>

        <section class="about">
            <h3>Panel de Administración - WinKnow</h3>
            <p>Desde este panel puedes gestionar usuarios, aulas, recursos y generar reportes del sistema. WinKnow te permite administrar de forma eficiente todos los recursos del ITSP.</p>

            <!-- Tarjetas de información del admin -->
            <div class="admin-info-cards">
                <div class="info-card">
                    <h4><i class="bi bi-person-badge"></i> Tu Información</h4>
                    <p><strong>Nombre:</strong> <?php echo isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'No disponible'; ?></p>
                    <p><strong>Cédula:</strong> <?php echo isset($_SESSION['cedula']) && !empty($_SESSION['cedula']) ? htmlspecialchars($_SESSION['cedula']) : 'No disponible'; ?></p>
                    <p><strong>Rol:</strong> <?php echo isset($_SESSION['rolAdmin']) && !empty($_SESSION['rolAdmin']) ? htmlspecialchars($_SESSION['rolAdmin']) : 'Administrador General'; ?></p>
                    <p><strong>Teléfono:</strong> <?php echo isset($_SESSION['telefono']) && !empty($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : 'No disponible'; ?></p>
                </div>
            </div>

            <!-- CARRUSEL -->
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
     
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="../img/itsp.png" alt="ITSP Campus">
                        <div class="carousel-caption"></div>
                    </div>

                    <div class="item">
                        <img src="../img/itsp2.jpeg" alt="ITSP Laboratorios">
                        <div class="carousel-caption"></div>
                    </div>

                    <div class="item">
                        <img src="../img/itsp3.jpeg" alt="ITSP Estudiantes">
                        <div class="carousel-caption"></div>
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </main>

</body>
</html>