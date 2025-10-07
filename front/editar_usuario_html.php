<title>WinKnow - Editar Usuario</title>

<body>

<div class="form-overlay">
    
    <!-- MENSAJES -->
    <?php if ($mensaje): ?>
        <div id="mensaje-data" 
             data-mensaje="<?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>" 
             data-tipo="<?= htmlspecialchars($tipo_mensaje, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
    <?php endif; ?>
    
    <form id="editarUsuarioForm" class="form-flotante" method="POST">
        <h2 style="text-align: center; margin-bottom: 20px;">
            <i class="bi bi-pencil-square"></i> <span data-lang="edit_user">Editar Usuario</span>
        </h2>
        
        <div class="info-usuario-editar">
            <p><strong data-lang="id_number">Cédula</strong>: <?= htmlspecialchars($usuario_datos['Cedula']); ?></p>
            <div class="etiqueta-tipo">
                <?= htmlspecialchars($usuario_datos['tipo_usuario']); ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="nombre" data-lang="full_name">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" data-lang="full_name" placeholder="Nombre Completo" 
                   value="<?= htmlspecialchars($usuario_datos['Nombre_usr'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email" data-lang="email">Email:</label>
            <input type="email" id="email" name="email" data-lang="email" placeholder="Email" 
                   value="<?= htmlspecialchars($usuario_datos['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="telefono" data-lang="phone">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" data-lang="phone" placeholder="Teléfono" 
                   value="<?= htmlspecialchars($usuario_datos['numeroTelefono'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="nueva_contrasenia" data-lang="new_password">Nueva Contraseña (opcional):</label>
            <input type="password" id="nueva_contrasenia" name="nueva_contrasenia" 
                   data-lang="leave_blank" placeholder="Dejar en blanco para mantener la actual">
        </div>
        
        <?php if ($usuario_datos['tipo_usuario'] === 'Docente'): ?>
            <div class="form-group">
                <label for="estado_docente" data-lang="status">Estado:</label>
                <select id="estado_docente" name="estado_docente" required>
                    <option value="Activo" <?= ($usuario_datos['estado_docente'] ?? '') === 'Activo' ? 'selected' : ''; ?> data-lang="active">
                        Activo
                    </option>
                    <option value="Inactivo" <?= ($usuario_datos['estado_docente'] ?? '') === 'Inactivo' ? 'selected' : ''; ?> data-lang="inactive">
                        Inactivo
                    </option>
                </select>
            </div>
            
        <?php elseif ($usuario_datos['tipo_usuario'] === 'Administrador'): ?>
            <div class="form-group">
                <label for="rolAdmin" data-lang="admin_role">Rol Administrativo:</label>
                <input type="text" id="rolAdmin" name="rolAdmin" data-lang="admin_role" placeholder="Rol Administrativo" 
                       value="<?= htmlspecialchars($usuario_datos['rolAdmin'] ?? ''); ?>">
            </div>
            
        <?php elseif ($usuario_datos['tipo_usuario'] === 'Estudiante'): ?>
            <div class="form-group">
                <label for="fechaNac" data-lang="birth_date">Fecha de Nacimiento:</label>
                <input type="date" id="fechaNac" name="fechaNac" 
                       value="<?= $usuario_datos['FechaNac'] ?? ''; ?>">
            </div>
        <?php endif; ?>
        
        <button type="submit" class="boton-primario">
            <i class="bi bi-check-circle"></i> <span data-lang="save_changes">Guardar Cambios</span>
        </button>
        
        <a href="../usuarios.php" class="boton-secundario" style="text-align: center; margin-top: 10px;">
            <i class="bi bi-arrow-left"></i> <span data-lang="back">Volver</span>
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>