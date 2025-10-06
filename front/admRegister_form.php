<?php
include '../front/header.html';
?>
<link rel="stylesheet" href="titleFX.css">

<title>WinKnow | Registro Admin</title>
<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="">
        <p class="cursor typewriter-animation">REGISTRO ADMIN</p>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?php echo htmlspecialchars($tipo_mensaje, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required maxlength="50" 
                   value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            
            <input type="password" name="contra" placeholder="Contraseña" required maxlength="20" minlength="7">
            
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required 
                   pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8"
                   value="<?php echo isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            
            <input type="text" name="rolAdm" placeholder="Rol admin" required
                   value="<?php echo isset($_POST['rolAdm']) ? htmlspecialchars($_POST['rolAdm'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        </div>

        <button type="submit">Registrar Admin</button>
        <br>
        <a href="login.php" class="link">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>
</div>

<script src="/login_reg/registerValidation.js"></script>
</body>
</html>