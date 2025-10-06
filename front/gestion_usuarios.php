<title>WinKnow - Gestión de Usuarios</title>

<body>

<?php include 'navADM.php'; ?>

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
                    <h3>Administradores</h3>
                    <div class="numero"><?= sprintf('%02d', $estadisticas['administradores'] ?? 0); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono disponible"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3>Docentes</h3>
                    <div class="numero"><?= sprintf('%02d', $estadisticas['docentes'] ?? 0); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono reservado"><i class="bi bi-people"></i></div>
                <div>
                    <h3>Estudiantes</h3>
                    <div class="numero"><?= sprintf('%02d', $estadisticas['estudiantes'] ?? 0); ?></div>
                </div>
            </div>
        </section>

        <br>

        <!-- ACCIONES -->
        <section class="acciones">
            <a href="../login_reg/register.php" class="boton-primario">
                <i class="bi bi-person-plus"></i> Agregar Usuario
            </a>
            <button onclick="location.reload()" class="boton-secundario">
                <i class="bi bi-arrow-clockwise"></i> Actualizar Lista
            </button>
        </section>

        <br>

        <!-- LISTA DE USUARIOS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-people"></i> Lista de Usuarios</h2>
                <p><strong>Total: <?= count($usuarios); ?> usuario(s) registrado(s)</strong></p>
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
                                    <p><strong>Cédula:</strong> <?= htmlspecialchars($u['Cedula']); ?></p>
                                    <p><strong>Tipo:</strong> <?= htmlspecialchars($u['tipo_usuario']); ?></p>
                                    
                                    <?php if ($u['email']): ?>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($u['email']); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($u['numeroTelefono']): ?>
                                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($u['numeroTelefono']); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($u['tipo_usuario'] === 'Administrador' && $u['rolAdmin']): ?>
                                        <p><strong>Rol:</strong> <?= htmlspecialchars($u['rolAdmin']); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="etiqueta">
                                    <?= htmlspecialchars($u['tipo_usuario']); ?>
                                </div>

                                    <div class="acciones-usuario">
                                        <a href="adm_usr/editar_usr.php?cedula=<?= $u['Cedula']; ?>" class="boton-secundario">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        
                                        <form method="POST" action="usuarios.php" class="form-eliminar-usuario">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="cedula" value="<?= $u['Cedula']; ?>">
                                            <button type="submit" class="boton-eliminar">
                                                <i class="bi bi-trash"></i> Eliminar
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
                    <p>No hay usuarios registrados.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>

</main>

</body>
</html>