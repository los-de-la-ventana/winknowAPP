<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}
include '../front/header.html';

?>

<?php
    include '../front/navadm.php';
?>
<body>
<title>WinKnow - Panel Admin</title>



    <!-- Contenido Principal -->
    <main class="principal">

        <section class="about">
            <h3 data-lang="admin_panel">Panel de Administración - WinKnow</h3>
            <p data-lang="admin_panel_text">Desde este panel puedes gestionar usuarios, aulas, recursos y generar reportes del sistema. WinKnow te permite administrar de forma eficiente todos los recursos del ITSP.</p>

            <!-- Tarjetas de información del admin -->  
            <div class="admin-info-cards">
                <div class="info-card">
                    <h4><i class="bi bi-person-badge"></i> <span data-lang="your_information">Tu Información</span></h4>
                    <p><strong data-lang="name">Nombre</strong>: <?php echo isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '<span data-lang="not_available">No disponible</span>'; ?></p>
                    <p><strong data-lang="id_number">Cédula</strong>: <?php echo isset($_SESSION['cedula']) && !empty($_SESSION['cedula']) ? htmlspecialchars($_SESSION['cedula']) : '<span data-lang="not_available">No disponible</span>'; ?></p>
                    <p><strong data-lang="role">Rol</strong>: <?php echo isset($_SESSION['rolAdmin']) && !empty($_SESSION['rolAdmin']) ? htmlspecialchars($_SESSION['rolAdmin']) : '<span data-lang="general_administrator">Administrador General</span>'; ?></p>
                </div>
            </div>

            <!-- CARRUSEL -->


            
            <?php include '../front/carrusel.html'; ?>
        </section>
    </main>

</body>
</html>