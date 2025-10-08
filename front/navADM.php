<!-- Barra Lateral -->
<aside class="barra-lateral">
    <div class="logo">
        <div class="icono-logo">WK</div>
        <span>WinKnow</span>
    </div>
    
    <nav class="navegacion">
        <ul>
            <a href="inicio.php"><li class="activo"><i class="bi bi-house"></i> <span data-lang="nav_inicio">Inicio</span></li></a> 
            <a href="aulas.php"><li><i class="bi bi-building"></i> <span data-lang="nav_aulas">Aulas</span></li></a>
            <a href="calendario.php"><li><i class="bi bi-calendar3"></i> <span data-lang="nav_calendario">Calendario</span></li></a>
            <a href="usuarios.php"><li><i class="bi bi-people"></i> <span data-lang="nav_usuarios">Administrar Usuarios</span></li></a>
            <a href="../login_reg/logout.php" id="logout-link"><li><i class="bi bi-box-arrow-right"></i> <span data-lang="nav_logout">Cerrar Sesión</span></li></a>
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
                        echo '<span class="usuario-blanco" data-lang="default_user">Usuario</span>';
                    }
                ?>
            </div>
            <div class="rol-usuario">
                <span data-lang="role_admin">Administrador</span>
                <?php if (isset($_SESSION['rolAdmin']) && !empty($_SESSION['rolAdmin'])): ?>
                    - <?php echo htmlspecialchars($_SESSION['rolAdmin']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</aside>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>