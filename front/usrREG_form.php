<?php
include 'header.html';
?>
<title>WinKnow - Registro de Usuarios</title>
<link rel="stylesheet" href="../login_reg/titleFX.css">
<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="register.php">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <p class="cursor typewriter-animation" style="margin: 0;">AÑADIR USUARIOS</p>
            <a href="../admin/usuarios.php" style="text-decoration: none; color: inherit; display: flex; align-items: center; padding: 8px; border-radius: 4px; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>
        </div>

 <?php if (!empty($mensaje)): ?>
    <div class="mensaje <?php echo $tipo_mensaje; ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>

        <!-- Selección de tipo -->
        <label for="operacion">Seleccione tipo de usuario</label>
        <select name="operacion" id="operacion" required>
            <option value="">-- Seleccione --</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>
        <br>
        <div id="divDeInputs" class="input-field"></div>

        <button type="submit">Registrar</button>
        <br>
    </form>

 
    <template id="template-docente">
        <div id="docente-form" class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required maxlength="20" minlength="7">
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8">
            <input type="text" name="estado" placeholder="Estado" required>
        </div>
    </template>

    <template id="template-estudiante">
        <div id="estudiante-form" class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required maxlength="20" minlength="7">
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8">

        </div>
    </template>
</div>

<script src="/login_reg/registerValidation.js"></script>
</body>
</html>