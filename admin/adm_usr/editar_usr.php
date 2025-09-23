<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// Incluir las funciones de usuarios
require_once 'funciones_usuarios.php';

$mensaje = '';
$tipo_mensaje = '';
$usuario_datos = [];

// Obtener la cédula del usuario a editar
$cedula = $_GET['cedula'] ?? null;

if (!$cedula) {
    header("Location: usuarios.php");
    exit;
}

// Obtener datos del usuario
$resultado_usuario = obtenerUsuario($cedula);
if (!$resultado_usuario['success']) {
    $_SESSION['mensaje'] = 'Usuario no encontrado';
    $_SESSION['tipo_mensaje'] = 'danger';
    header("Location: usuarios.php");
    exit;
}

$usuario_datos = $resultado_usuario['data'];

// Procesar modificación si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos_modificacion = [
        'nombre' => $_POST['nombre'] ?? null,
        'email' => $_POST['email'] ?? null,
        'telefono' => $_POST['telefono'] ?? null,
        'tipo_usuario' => $usuario_datos['tipo_usuario'], // Mantenemos el tipo original
    ];
    
    // Agregar contraseña nueva si se proporcionó
    if (!empty($_POST['nueva_contrasenia'])) {
        $datos_modificacion['nueva_contrasenia'] = $_POST['nueva_contrasenia'];
    }
    
    // Datos específicos según el tipo de usuario
    switch ($usuario_datos['tipo_usuario']) {
        case 'Docente':
            $datos_modificacion['grado'] = $_POST['grado'] ?? null;
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
    
    // Si fue exitoso, actualizar los datos mostrados
    if ($resultado['success']) {
        $resultado_usuario = obtenerUsuario($cedula);
        $usuario_datos = $resultado_usuario['data'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/wk_logo.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../inicio.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinKnow - Editar Usuario</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .usuario-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .btn-group-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .tipo-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .tipo-docente { background: #d4edda; color: #155724; }
        .tipo-admin { background: #f8d7da; color: #721c24; }
        .tipo-estudiante { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>

    <!-- Barra Lateral -->
    <aside class="barra-lateral">
        <div class="logo">
            <div class="icono-logo">WK</div>
            <span>WinKnow</span>
        </div>
        
        <nav class="navegacion">
            <ul>
                <a href="inicio.php"><li><i class="bi bi-house"></i> Inicio</li></a> 
                <a href="aulas.php"><li><i class="bi bi-building"></i> Aulas</li></a>
                <a href="calendario.php"><li><i class="bi bi-calendar3"></i> Calendario</li></a>
                <a href="reportes.php"><li><i class="bi bi-bar-chart"></i> Reportes</li></a>
                <a href="usuarios.php"><li class="activo"><i class="bi bi-people"></i> Administrar Usuarios</li></a>
                <a href="../login_reg/logout.php"><li><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</li></a>
            </ul>
        </nav> 
        
        <div class="usuario">
            <div class="info-usuario">
                <div class="nombre-usuario">
                    <i class="bi bi-person-circle"></i>
                    <?php   
                        if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
                            echo htmlspecialchars($_SESSION['nombre']);
                        } else {
                            echo '<span class="usuario-blanco">Usuario</span>';
                        }
                    ?>
                </div>
                <div class="rol-usuario">
                    Administrador
                    <?php if (isset($_SESSION['rolAdmin']) && !empty($_SESSION['rolAdmin'])): ?>
                        - <?php echo htmlspecialchars($_SESSION['rolAdmin']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="principal">
        <header class="encabezado">
            <h1>Editar Usuario</h1>
            <button id="toggle-theme" class="boton-primario">Cambiar tema</button>
            <script src="../lightmode.js"></script>
        </header>

        <section class="contenido">
            
            <div class="form-container">
                
                <!-- Mensajes de estado -->
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>
                <?php endif; ?>

                <div class="form-header">
                    <h3><i class="bi bi-person-gear"></i> Editar Usuario</h3>
                </div>
                
                <!-- Información actual del usuario -->
                <div class="usuario-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Cédula:</strong> <?php echo htmlspecialchars($usuario_datos['Cedula']); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Tipo:</strong> 
                            <span class="tipo-badge tipo-<?php echo strtolower($usuario_datos['tipo_usuario']); ?>">
                                <?php echo htmlspecialchars($usuario_datos['tipo_usuario']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de edición -->
                <form method="POST">
                    
                    <!-- Datos básicos -->
                    <div class="form-group">
                        <label for="nombre">Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario_datos['Nombre_usr'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario_datos['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario_datos['numeroTelefono'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="nueva_contrasenia">Nueva Contraseña:</label>
                        <input type="password" id="nueva_contrasenia" name="nueva_contrasenia" class="form-control" 
                               placeholder="Dejar en blanco para mantener la actual">
                        <small class="text-muted">Solo completa este campo si deseas cambiar la contraseña</small>
                    </div>
                    
                    <hr>
                    
                    <!-- Campos específicos según el tipo de usuario -->
                    <?php if ($usuario_datos['tipo_usuario'] === 'Docente'): ?>
                        <h4><i class="bi bi-mortarboard"></i> Información de Docente</h4>
                        
                        <div class="form-group">
                            <label for="grado">Grado:</label>
                            <select id="grado" name="grado" class="form-control">
                                <option value="">Seleccionar grado...</option>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>" 
                                            <?php echo ($usuario_datos['grado'] == $i) ? 'selected' : ''; ?>>
                                        Grado <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado_docente">Estado:</label>
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
                            <div class="form-group">
                                <label>Fecha de Ingreso:</label>
                                <p class="form-control-static">
                                    <?php echo date('d/m/Y', strtotime($usuario_datos['AnioInsercion'])); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                    <?php elseif ($usuario_datos['tipo_usuario'] === 'Administrador'): ?>
                        <h4><i class="bi bi-shield-check"></i> Información de Administrador</h4>
                        
                        <div class="form-group">
                            <label for="rolAdmin">Rol Administrativo:</label>
                            <input type="text" id="rolAdmin" name="rolAdmin" class="form-control" 
                                   value="<?php echo htmlspecialchars($usuario_datos['rolAdmin'] ?? ''); ?>"
                                   placeholder="Ej: Director, Coordinador, Secretario">
                        </div>
                        
                    <?php elseif ($usuario_datos['tipo_usuario'] === 'Estudiante'): ?>
                        <h4><i class="bi bi-book"></i> Información de Estudiante</h4>
                        
                        <div class="form-group">
                            <label for="fechaNac">Fecha de Nacimiento:</label>
                            <input type="date" id="fechaNac" name="fechaNac" class="form-control" 
                                   value="<?php echo $usuario_datos['FechaNac'] ?? ''; ?>">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Botones de acción -->
                    <div class="btn-group-actions">
                        <a href="usuarios.php" class="btn btn-default">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar Cambios
                        </button>
                    </div>
                    
                </form>
            </div>

        </section>
    </main>

    <script>
        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Confirmación antes de guardar cambios
        $('form').on('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas guardar estos cambios?')) {
                e.preventDefault();
            }
        });
    </script>

</body>
</html>