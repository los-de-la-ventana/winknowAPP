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
                   <!-- Calendario disponible para todos los tipos de usuario -->
                <a href="calendario.php">  <li><i class="bi bi-calendar3"></i> Calendario</li> </a>
               <!-- Cerrar sesión disponible para todos los tipos de usuario -->
            <a href="../login_reg/logout.php" id="logout-link"><li><i class="bi bi-box-arrow-right"></i> <span data-lang="nav_logout">Cerrar Sesión</span></li></a>
            </ul>
        </nav> 

        
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>