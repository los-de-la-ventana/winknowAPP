<?php
session_start();
require 'adm_usr/func_usr.php';

if (!($_SESSION['logged_in'] ?? false) || ($_SESSION['tipo'] ?? '') !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'agregar':
            $cedula = trim($_POST['cedula'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $contrasenia = $_POST['contra'] ?? '';
            $tipo_usuario = strtolower($_POST['tipo_usuario'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            
            if (empty($cedula) || empty($nombre) || empty($contrasenia) || empty($tipo_usuario)) {
                $mensaje = 'Todos los campos obligatorios deben estar completos';
                $tipo_mensaje = 'danger';
                break;
            }
            
            if (!ctype_digit($cedula)) {
                $mensaje = 'La cédula solo debe contener números';
                $tipo_mensaje = 'danger';
                break;
            }
            
            $datos_adicionales = [];
            
            switch ($tipo_usuario) {
                case 'docente':
                    $datos_adicionales['anioIns'] = $_POST['anioIns'] ?? date('Y-m-d');
                    $datos_adicionales['estado'] = $_POST['estado'] ?? 'Activo';
                    break;
                    
                case 'admin':
                    $datos_adicionales['rolAdmin'] = $_POST['rolAdm'] ?? 'ADMIN';
                    break;
                    
                case 'estudiante':
                    $datos_adicionales['fechaNac'] = $_POST['fnac'] ?? null;
                    if (empty($datos_adicionales['fechaNac'])) {
                        $mensaje = 'La fecha de nacimiento es obligatoria para estudiantes';
                        $tipo_mensaje = 'danger';
                        break 2;
                    }
                    break;
                    
                default:
                    $mensaje = 'Tipo de usuario no válido';
                    $tipo_mensaje = 'danger';
                    break 2;
            }
            
            $resultado = agregarUsuario($cedula, $contrasenia, $nombre, $tipo_usuario, $email, $telefono, $datos_adicionales);
            $mensaje = $resultado['message'];
            $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
            break;
            
        case 'eliminar':
            $cedula = $_POST['cedula'] ?? '';
            if (empty($cedula)) {
                $mensaje = 'No se especificó la cédula del usuario';
                $tipo_mensaje = 'danger';
                break;
            }
            
            $resultado = eliminarUsuario($cedula);
            $mensaje = $resultado['message'];
            $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
            break;
            
        case 'cambiar_estado':
            $cedula = $_POST['cedula'] ?? '';
            $nuevo_estado = $_POST['nuevo_estado'] ?? '';
            
            if (empty($cedula) || empty($nuevo_estado)) {
                $mensaje = 'Datos incompletos para cambiar estado';
                $tipo_mensaje = 'danger';
                break;
            }
            
            $resultado = cambiarEstadoDocente($cedula, $nuevo_estado);
            $mensaje = $resultado['message'];
            $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
            break;
            
        default:
            $mensaje = 'Acción no válida';
            $tipo_mensaje = 'danger';
    }
}

$usuarios = listarUsuarios()['data'] ?? [];
$estadisticas = obtenerEstadisticasUsuarios()['data'] ?? [];

include '../front/header.html';
?>

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
        <a href="adm_usr/editar_usr.php">
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
                            <?php if ($u['tipo_usuario']==='Docente' && $u['estado_docente']): ?>
                                <p><strong>Estado:</strong>
                                    <span class="badge <?php echo $u['estado_docente']==='Activo'?'bg-success':'bg-warning text-dark'; ?>">
                                        <?php echo htmlspecialchars($u['estado_docente']); ?>
                                    </span>
                                </p>
                            <?php endif; ?>
                            <?php if ($u['tipo_usuario']==='Administrador' && $u['rolAdmin']): ?>
                                <p><strong>Rol:</strong> <?php echo htmlspecialchars($u['rolAdmin']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <a href="adm_usr/editar_usr.php?cedula=<?php echo $u['Cedula']; ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($u['tipo_usuario']==='Docente'): ?>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Cambiar estado del docente?');">
                                    <input type="hidden" name="accion" value="cambiar_estado">
                                    <input type="hidden" name="cedula" value="<?php echo $u['Cedula']; ?>">
                                    <input type="hidden" name="nuevo_estado" value="<?php echo $u['estado_docente']==='Activo'?'Inactivo':'Activo'; ?>">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-toggle-on"></i>
                                    </button>
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
                            <label class="form-label">Estado:</label>
                            <input type="text" name="estado" class="form-control" value="Activo">
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