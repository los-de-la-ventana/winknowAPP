<title>WinKnow - Gestión de Usuarios</title>

<body>

<?php include 'navadm.php'; ?>

<main class="principal">
    
    <div class="contenido">

        <!-- MENSAJES -->
        <?php if ($mensaje): ?>
            <div id="mensaje-data" 
                 data-mensaje="<?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>" 
                 data-tipo="<?= htmlspecialchars($tipo_mensaje === 'success' ? 'exito' : 'error', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        <?php endif; ?>

        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-person-gear"></i></div>
                <div>
                    <h3 data-lang="administrators">Administradores</h3>
                    <div class="numero"><?= sprintf('%02d', $estadisticas['administradores'] ?? 0); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono disponible"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3 data-lang="teachers">Docentes</h3>
                    <div class="numero"><?= sprintf('%02d', $estadisticas['docentes'] ?? 0); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono reservado"><i class="bi bi-people"></i></div>
                <div>
                    <h3 data-lang="students">Estudiantes</h3>
                    <div class="numero"><?= sprintf('%02d', $estadisticas['estudiantes'] ?? 0); ?></div>
                </div>
            </div>
        </section>

        <br>

<!-- ACCIONES -->
<section class="acciones">
    <a href="../login_reg/register.php" class="boton-primario">
        <i class="bi bi-person-plus"></i> <span data-lang="add_user">Agregar Usuario</span>
    </a>
    <button type="button" onclick="window.location.reload();" class="boton-secundario">
        <i class="bi bi-arrow-clockwise"></i> <span data-lang="refresh_list">Actualizar Lista</span>
    </button>
</section>

        <br>

        <!-- LISTA DE USUARIOS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-people"></i> <span data-lang="user_list">Lista de Usuarios</span></h2>
                <p><strong><span data-lang="total">Total</span>: <?= count($usuarios); ?> <span data-lang="registered_users">usuario(s) registrado(s)</span></strong></p>
            </div>

            <?php if ($usuarios): ?>
                <div class="grilla">
                    <?php foreach ($usuarios as $u): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="bi bi-person-circle"></i>
                                    <?= htmlspecialchars($u['Nombre_usr']); ?>
                                </h4>
                                
                                <div class="detalles">
                                    <p><strong data-lang="id_number">Cédula</strong>: <?= htmlspecialchars($u['Cedula']); ?></p>
                                    <p><strong data-lang="type">Tipo</strong>: <?= htmlspecialchars($u['tipo_usuario']); ?></p>
                                    
                                    <?php if ($u['email']): ?>
                                        <p><strong data-lang="email">Email</strong>: <?= htmlspecialchars($u['email']); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($u['numeroTelefono']): ?>
                                        <p><strong data-lang="phone">Teléfono</strong>: <?= htmlspecialchars($u['numeroTelefono']); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($u['tipo_usuario'] === 'Administrador' && $u['rolAdmin']): ?>
                                        <p><strong data-lang="role">Rol</strong>: <?= htmlspecialchars($u['rolAdmin']); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="etiqueta">
                                    <?= htmlspecialchars($u['tipo_usuario']); ?>
                                </div>

                                    <div class="acciones-usuario">
                                        
                                        
                                        <form method="POST" action="usuarios.php" class="form-eliminar-usuario">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="cedula" value="<?= $u['Cedula']; ?>">
                                            <button type="submit" class="boton-eliminar">
                                                <i class="bi bi-trash"></i> <span data-lang="delete">Eliminar</span>
                                            </button>
                                        </form>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="mensaje-vacio">
                    <i class="bi bi-inbox"></i>
                    <p data-lang="no_users_registered">No hay usuarios registrados.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>

</main>

</body>
</html>