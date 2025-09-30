<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../../login_reg/login.php");
    exit;
}

require_once 'func_usr.php';

$mensaje = '';
$tipo_mensaje = '';
$usuario_datos = [];


$cedula = $_GET['cedula'] ?? null;

if (!$cedula) {
    $_SESSION['mensaje'] = 'No se especificó un usuario';
    $_SESSION['tipo_mensaje'] = 'danger';
    header("Location: ../../login_reg/login.php");
    exit;
}

$resultado_usuario = obtenerUsuario($cedula);
if (!$resultado_usuario['success']) {
    $_SESSION['mensaje'] = 'Usuario no encontrado';
    $_SESSION['tipo_mensaje'] = 'danger';
    header("Location: ../../login_reg/login.php");
    exit;
}

$usuario_datos = $resultado_usuario['data'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos_modificacion = [
        'nombre' => $_POST['nombre'] ?? null,
        'email' => $_POST['email'] ?? null,
        'telefono' => $_POST['telefono'] ?? null,
        'tipo_usuario' => $usuario_datos['tipo_usuario'],
    ];
    
    if (!empty($_POST['nueva_contrasenia'])) {
        $datos_modificacion['nueva_contrasenia'] = $_POST['nueva_contrasenia'];
    }
    
    switch ($usuario_datos['tipo_usuario']) {
        case 'Docente':
            $datos_modificacion['estado_docente'] = $_POST['estado_docente'] ?? null;
            break;
        case 'Administrador':
            $datos_modificacion['rolAdmin'] = $_POST['rolAdmin'] ?? null;
            break;
        case 'Estudiante':
            $datos_modificacion['fechaNac'] = $_POST['fechaNac'] ?? null;
            break;
    }
    
    $resultado = modificarUsuario($cedula, $datos_modificacion);
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
    
    if ($resultado['success']) {
        $resultado_usuario = obtenerUsuario($cedula);
        $usuario_datos = $resultado_usuario['data'];
    }
}

include '../headerfooter/header.html';
?>

<body>
<title>WinKnow - Editar Usuario</title>

<main class="principal container py-4">
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-person-gear"></i> Editar Usuario</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Cédula:</strong> <?php echo htmlspecialchars($usuario_datos['Cedula']); ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Tipo:</strong> 
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars($usuario_datos['tipo_usuario']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" 
                                   value="<?php echo htmlspecialchars($usuario_datos['Nombre_usr'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($usuario_datos['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" 
                                   value="<?php echo htmlspecialchars($usuario_datos['numeroTelefono'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="nueva_contrasenia" class="form-label">Nueva Contraseña:</label>
                            <input type="password" id="nueva_contrasenia" name="nueva_contrasenia" class="form-control" 
                                   placeholder="Dejar en blanco para mantener la actual">
                            <small class="text-muted">Solo completa este campo si deseas cambiar la contraseña</small>
                        </div>
                        
                        <hr>
                        
                        <?php if ($usuario_datos['tipo_usuario'] === 'Docente'): ?>
                            <h5><i class="bi bi-mortarboard"></i> Información de Docente</h5>
                            
                            <div class="mb-3">
                                <label for="estado_docente" class="form-label">Estado:</label>
                                <select id="estado_docente" name="estado_docente" class="form-control">
                                    <option value="Activo" <?php echo ($usuario_datos['estado_docente'] === 'Activo') ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="Inactivo" <?php echo ($usuario_datos['estado_docente'] === 'Inactivo') ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                            
                            <?php if ($usuario_datos['AnioInsercion']): ?>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Ingreso:</label>
                                    <p class="form-control-static">
                                        <?php echo date('d/m/Y', strtotime($usuario_datos['AnioInsercion'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                        <?php elseif ($usuario_datos['tipo_usuario'] === 'Administrador'): ?>
                            <h5><i class="bi bi-shield-check"></i> Información de Administrador</h5>
                            
                            <div class="mb-3">
                                <label for="rolAdmin" class="form-label">Rol Administrativo:</label>
                                <input type="text" id="rolAdmin" name="rolAdmin" class="form-control" 
                                       value="<?php echo htmlspecialchars($usuario_datos['rolAdmin'] ?? ''); ?>"
                                       placeholder="Ej: Director, Coordinador, Secretario">
                            </div>
                            
                        <?php elseif ($usuario_datos['tipo_usuario'] === 'Estudiante'): ?>
                            <h5><i class="bi bi-book"></i> Información de Estudiante</h5>
                            
                            <div class="mb-3">
                                <label for="fechaNac" class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" id="fechaNac" name="fechaNac" class="form-control" 
                                       value="<?php echo $usuario_datos['FechaNac'] ?? ''; ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="../adm_usr.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../headerfooter/navADM.php'; ?>

<script>
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    for (var i = 0; i < alerts.length; i++) {
        alerts[i].style.display = 'none';
    }
}, 5000);

var form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        if (!confirm('¿Estás seguro de que deseas guardar estos cambios?')) {
            e.preventDefault();
        }
    });
}
</script>

</body>
</html>