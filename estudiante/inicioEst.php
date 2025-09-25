<?php
session_start();
include '../headerfooter/header.html';
?>

<title>WinKnow - Inicio</title>

<body>

        <!-- Barra Lateral -->
    <aside class="barra-lateral">
        <div class="logo">
            <div class="icono-logo">WK</div>
            <span>WinKnow</span>
        </div>
        
    <nav class="navegacion">
            <ul>
                <a href="inicioEst.php"> <li><i class="bi bi-house"></i> Inicio</li></a> 
                
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

<div class="usuario usuario-estudiante">
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
       <?php include '../headerfooter/carrusel.html'; ?>

        </div>
        </main>
    </body>
</html>