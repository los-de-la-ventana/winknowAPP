<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/wk_logo.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WinKnow - Registro</title>
    <link rel="stylesheet" href="../inicio.css">
    <link rel="stylesheet" href="titleFX.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<header>
    <button id="toggle-theme" class="boton-primario">
        Cambiar tema
    </button>
    <script src="../lightmode.js"></script>
</header>
<body>

<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="register.php">
        <p class="cursor typewriter-animation">REGÍSTRATE</p>

 <?php if (!empty($mensaje)): ?>
    <div class="mensaje <?php echo $tipo_mensaje; ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>

        <!-- Selección de tipo -->
        <label for="operacion">Seleccione tipo de usuario</label>
        <select name="operacion" id="operacion" required>
            <option value="">-- Seleccione --</option>
            <option value="admin">Administrador</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>
        <br>
        <div id="divDeInputs" class="input-field"></div>

        <button type="submit">Registrar</button>
        <br>
        <a href="login.php" class="link">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>

    <template id="template-admin">
        <div id="admin-form" class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8">
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="text" name="rolAdm" placeholder="Rol admin" required>
        </div>
    </template>

    <template id="template-docente">
        <div id="docente-form" class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8">
            <input type="text" name="estado" placeholder="Estado" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="number" name="anioIns" placeholder="Año de inserción" required min="1900" max="2025">
        </div>
    </template>

    <template id="template-estudiante">
        <div id="estudiante-form" class="input-field">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones" maxlength="8" minlength="8">
            <input type="date" name="fnac" placeholder="Fecha nacimiento" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
        </div>
    </template>
</div>

<script src="/login_reg/registerValidation.js"></script>
</body>
</html>