
<?php
include '../front/header.html';

?>
<link rel="stylesheet" href="titleFX.css">

<title>WinKnow | Registro Admin</title>
<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="">
        <p class="cursor typewriter-animation">REGISTRO ADMIN</p>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required maxlength="20" minlength="7">
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8">
            <input type="text" name="rolAdm" placeholder="Rol admin" required>
        </div>

        <button type="submit">Registrar Admin</button>
        <br>
        <a href="login.php" class="link">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>
</div>

<script src="/login_reg/registerValidation.js"></script>
</body>
</html>