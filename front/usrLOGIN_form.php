
<link rel="stylesheet" href="titleFX.css">
<title>WinKnow - Login</title>

<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="login.php">
        <p class="cursor typewriter-animation">INICIA SESION</p>
        
      <?php if (!empty($mensaje)): ?>
    <div class="mensaje <?php echo $tipo_mensaje; ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>
        
        <input type="number" name="cedula" placeholder="Cédula" required maxlength="8" minlength="8">
        <input type="password" name="contra" placeholder="Contraseña" required maxlength="20" minlength="7">
        <button type="submit">Ingresar</button>
        <a href="register.php" class="link">¿No tienes una cuenta? Regístrate</a>
    </form>
</div>

<script src="registerValidation.js"></script>

</body>
</html>