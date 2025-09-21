<?php
session_start();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/wk_logo.ico">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> <!--CSS DEL CARRUSEL DE W3SCHOOLS-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="../inicio.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinKnow - Inicio</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<header>
    <!-- Botón para cambiar tema a claro u oscuro -->
            <button id="toggle-theme" class="boton-primario">
                Cambiar tema
            </button>
            <script src="../lightmode.js"></script>
</header>
<body>

        <!-- Barra Lateral -->
    <aside class="barra-lateral">
        <div class="logo">
            <div class="icono-logo">WK</div>
            <span>WinKnow</span>
        </div>
        
    <nav class="navegacion">
            <ul>
                <a href="inicioDoc.php"> <li><i class="bi bi-house"></i> Inicio</li></a> 
                
                <?php
                // Verificar si el usuario es estudiante
                if (isset($_SESSION['tipo']) && $_SESSION['tipo'] !== 'estudiante') {
                    // Mostrar opciones completas para admin y docente
                    echo '<a href="aulas.php"> <li class="activo"><i class="bi bi-building"></i> Aulas</li></a>';
                    echo '<a href="reportes.php">  <li><i class="bi bi-bar-chart"></i> Reportes</li></a>';
                    
                    // Mostrar administrar usuario solo para admin
                    if ($_SESSION['tipo'] === 'admin') {
                        echo '<a href="admin_usuarios.php">  <li><i class="bi bi-people"></i> Administrar usuario</li></a>';
                    }
                }
                ?>
                
                <!-- Calendario disponible para todos los tipos de usuario -->
                <a href="calendario.php">  <li><i class="bi bi-calendar3"></i> Calendario</li> </a>
                <!-- Cerrar sesión disponible para todos los tipos de usuario -->
                <a href="../login_reg/logout.php"><li><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</li></a>

            </ul>
        </nav> 
        
<div class="usuario">
    <div class="nombre-usuario">
        <?php
        if (isset($_SESSION['nombre'])) {
            echo htmlspecialchars($_SESSION['nombre']);
        } else {
            echo "Invitado";
        }
        ?>
    </div>
    <div class="tipo-usuario">
        <?php
        if (isset($_SESSION['tipo'])) {
            echo "(" . ucfirst(htmlspecialchars($_SESSION['tipo'])) . ")";
        }
        ?>
    </div>
</div>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="principal">
<header class="encabezado">
    <h1>
        <?php
        if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
            echo "Hola, " . htmlspecialchars($_SESSION['nombre']);
        } else {
            echo "Bienvenido";
        }
        ?>
    </h1>
</header>
        <section class="about">
            <h3>WinKnow es una aplicación web desarrollada como proyecto de egreso del ITSP que organiza de forma eficiente aulas, laboratorios y recursos. Permite a estudiantes, docentes y personal administrativo consultar horarios, reservar espacios y optimizar el uso de los recursos del instituto.</h3>
           <!--CARRUSEL -->
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
                <img src="../img/itsp.png" alt="Chania">
                <div class="carousel-caption">

                </div>
                </div>

                <div class="item">
                <img src="../img/itsp2.jpg" alt="Chicago">
                <div class="carousel-caption">

                </div>
                </div>

                <div class="item">
                <img src="../img/itsp3.jpeg" alt="New York">
                <div class="carousel-caption">
            
                </div>
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


        </div>
        </main>
    </body>
</html>