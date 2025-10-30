<link rel="stylesheet" href="titleFX.css">
<title>WinKnow - Login</title>

<div class="form-overlay">
    
    <!-- MENSAJES OCULTOS PARA JAVASCRIPT -->
    <?php if (!empty($mensaje)): ?>
        <div id="mensaje-data" 
             data-mensaje="<?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>" 
             data-tipo="<?= htmlspecialchars($tipo_mensaje, ENT_QUOTES, 'UTF-8'); ?>" 
             style="display: none;">
        </div>
    <?php endif; ?>
    
    <form id="registroForm" class="form-flotante" method="post" action="login.php">
        <p class="cursor typewriter-animation" data-lang="login">INICIA SESION</p>
        
        <input type="number" name="cedula" data-lang="id_number" placeholder="Cédula" required maxlength="8" minlength="8">
        <input type="password" name="contra" data-lang="password" placeholder="Contraseña" required maxlength="20" minlength="7">
        <button type="submit" data-lang="enter">Ingresar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="registerValidation.js"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>