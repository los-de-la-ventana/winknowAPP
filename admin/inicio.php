<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}
include '../headerfooter/header.html';

?>

<?php
 include '../headerfooter/navADM.php';
?>
<body>
<title>WinKnow - Panel Admin</title>



    <!-- Contenido Principal -->
    <main class="principal">

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
            <?php include '../headerfooter/carrusel.html'; ?>
        </section>
    </main>

</body>
</html>