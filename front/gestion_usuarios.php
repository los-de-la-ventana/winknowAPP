
<body>
<title>WinKnow - Gestión de Usuarios</title>

<main class="principal container py-4">
    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2 class="mb-4">Gestión de Usuarios</h2>

    <div class="d-flex mb-3 gap-2">
        <a href="../login_reg/register.php">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            <i class="bi bi-person-plus"></i> Agregar Usuario
        </button>
        </a>
        <button class="btn btn-info" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Actualizar Lista
        </button>
    </div>

    <hr>

    <h3>Lista de Usuarios</h3>
    <?php if ($usuarios): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <?php foreach ($usuarios as $u): ?>
                <div class="col">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($u['Nombre_usr']); ?></h5>
                            <p class="card-text"><strong>Cédula:</strong> <?php echo htmlspecialchars($u['Cedula']); ?></p>
                            <p class="card-text"><strong>Tipo:</strong> <?php echo htmlspecialchars($u['tipo_usuario']); ?></p>
                            <?php if ($u['email']): ?><p><strong>Email:</strong> <?php echo htmlspecialchars($u['email']); ?></p><?php endif; ?>
                            <?php if ($u['numeroTelefono']): ?><p><strong>Teléfono:</strong> <?php echo htmlspecialchars($u['numeroTelefono']); ?></p><?php endif; ?>
                            
                                    </span>
                                </p>
                            <?php if ($u['tipo_usuario']==='Administrador' && $u['rolAdmin']): ?>
                                <p><strong>Rol:</strong> <?php echo htmlspecialchars($u['rolAdmin']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <a href="adm_usr/editar_usr.php?cedula=<?php echo $u['Cedula']; ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($u['tipo_usuario']==='Docente'): ?>
                               
                                    <input type="hidden" name="cedula" value="<?php echo $u['Cedula']; ?>">
                                   
                                </form>
                            <?php endif; ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="cedula" value="<?php echo $u['Cedula']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">No hay usuarios registrados.</div>
    <?php endif; ?>
</main>

<?php include '../front/navADM.php'; ?>

<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formAgregar">
                <input type="hidden" name="accion" value="agregar">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Usuario:</label>
                        <select name="tipo_usuario" class="form-select" required id="tipoUsuario">
                            <option value="">Seleccionar...</option>
                            <option value="docente">Docente</option>
                            <option value="admin">Administrador</option>
                            <option value="estudiante">Estudiante</option>
                        </select>
                    </div>

                    <div id="campos-docente" style="display:none;">
                        <h5>Datos del Docente</h5>
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" name="contra" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cédula (solo números):</label>
                            <input type="text" name="cedula" class="form-control" pattern="[0-9]+" title="Solo números sin puntos ni guiones">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono:</label>
                            <input type="tel" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año de inserción:</label>
                            <input type="number" name="anioIns" class="form-control" min="1900" max="2025" value="<?php echo date('Y'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div id="campos-admin" style="display:none;">
                        <h5>Datos del Administrador</h5>
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" name="contra" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cédula (solo números):</label>
                            <input type="text" name="cedula" class="form-control" pattern="[0-9]+" title="Solo números sin puntos ni guiones">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono:</label>
                            <input type="tel" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol admin:</label>
                            <input type="text" name="rolAdm" class="form-control" placeholder="Ej: Director, Coordinador">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div id="campos-estudiante" style="display:none;">
                        <h5>Datos del Estudiante</h5>
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" name="contra" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cédula (solo números):</label>
                            <input type="text" name="cedula" class="form-control" pattern="[0-9]+" title="Solo números sin puntos ni guiones">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha nacimiento:</label>
                            <input type="date" name="fnac" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono:</label>
                            <input type="tel" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Agregar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="adminValidation.js"></script>


</body>
</html>