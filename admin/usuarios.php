<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// Incluir las funciones de usuarios
require 'adm_usr/func_usr.php';

// Procesar acciones
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'agregar':
            $resultado = agregarUsuario(
                $_POST['cedula'],
                $_POST['contrasenia'],
                $_POST['nombre'],
                $_POST['tipo_usuario'],
                $_POST['email'] ?? null,
                $_POST['telefono'] ?? null,
                [
                    'grado' => $_POST['grado'] ?? null,
                    'estado' => $_POST['estado'] ?? 'Activo',
                    'rolAdmin' => $_POST['rolAdmin'] ?? null,
                    'fechaNac' => $_POST['fechaNac'] ?? null
                ]
            );
            $mensaje = $resultado['message'];
            $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
            break;
            
        case 'eliminar':
            $resultado = eliminarUsuario($_POST['cedula']);
            $mensaje = $resultado['message'];
            $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
            break;
            
        case 'cambiar_estado':
            $resultado = cambiarEstadoDocente($_POST['cedula'], $_POST['nuevo_estado']);
            $mensaje = $resultado['message'];
            $tipo_mensaje = $resultado['success'] ? 'success' : 'danger';
            break;
    }
}

// Obtener lista de usuarios para mostrar
$usuarios_resultado = listarUsuarios();
$usuarios = $usuarios_resultado['success'] ? $usuarios_resultado['data'] : [];

// Obtener estadísticas
$stats_resultado = obtenerEstadisticasUsuarios();
$estadisticas = $stats_resultado['success'] ? $stats_resultado['data'] : [];
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
    <title>WinKnow - Gestión de Usuarios</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
   
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
            <h1>Administrar Usuarios</h1>
            <button id="toggle-theme" class="boton-primario">Cambiar tema</button>
            <script src="../lightmode.js"></script>
        </header>

        <section class="contenido">
            
            <!-- Mensajes de estado -->
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <h2>Gestión de Usuarios</h2>

           

            <!-- Botones de acción principales -->
            <div class="acciones-usuario" style="margin: 20px 0;">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregar">
                    <i class="bi bi-person-plus"></i> Agregar Usuario
                </button>
                <button class="btn btn-info" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Actualizar Lista
                </button>
            </div>

            <hr>

            <!-- Lista de usuarios -->
            <h3>Lista de Usuarios</h3>
            
            <?php if (!empty($usuarios)): ?>
                <div class="usuarios-grid">
                    <?php foreach ($usuarios as $usuario): ?>
                        <div class="usuario-card <?php echo strtolower($usuario['tipo_usuario']); ?>">
                            <h4><?php echo htmlspecialchars($usuario['Nombre_usr']); ?></h4>
                            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($usuario['Cedula']); ?></p>
                            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($usuario['tipo_usuario']); ?></p>
                            
                            <?php if ($usuario['email']): ?>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($usuario['numeroTelefono']): ?>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['numeroTelefono']); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($usuario['tipo_usuario'] === 'Docente' && $usuario['estado_docente']): ?>
                                <p><strong>Estado:</strong> 
                                    <span class="label label-<?php echo $usuario['estado_docente'] === 'Activo' ? 'success' : 'warning'; ?>">
                                        <?php echo htmlspecialchars($usuario['estado_docente']); ?>
                                    </span>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($usuario['tipo_usuario'] === 'Administrador' && $usuario['rolAdmin']): ?>
                                <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['rolAdmin']); ?></p>
                            <?php endif; ?>
                            
                            <!-- Botones de acción -->
                            <div class="btn-actions">
                                <button class="btn btn-primary btn-sm" onclick="editarUsuario(<?php echo $usuario['Cedula']; ?>)">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                
                                <?php if ($usuario['tipo_usuario'] === 'Docente'): ?>
                                    <button class="btn btn-warning btn-sm" onclick="cambiarEstado(<?php echo $usuario['Cedula']; ?>, '<?php echo $usuario['estado_docente'] === 'Activo' ? 'Inactivo' : 'Activo'; ?>')">
                                        <i class="bi bi-toggle-off"></i> 
                                        <?php echo $usuario['estado_docente'] === 'Activo' ? 'Desactivar' : 'Activar'; ?>
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?php echo $usuario['Cedula']; ?>)">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay usuarios registrados.
                </div>
            <?php endif; ?>

        </section>
    </main>

    <!-- Modal para agregar usuario -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Nuevo Usuario</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="agregar">
                        
                        <div class="form-group">
                            <label>Cédula:</label>
                            <input type="number" name="cedula" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Contraseña:</label>
                            <input type="password" name="contrasenia" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tipo de Usuario:</label>
                            <select name="tipo_usuario" class="form-control" required onchange="mostrarCamposEspecificos(this.value)">
                                <option value="">Seleccionar...</option>
                                <option value="docente">Docente</option>
                                <option value="admin">Administrador</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        
                        <!-- Campos específicos para docente -->
                        <div id="campos-docente" style="display: none;">
                            <div class="form-group">
                                <label>Grado:</label>
                                <input type="number" name="grado" class="form-control" min="1" max="5">
                            </div>
                            <div class="form-group">
                                <label>Estado:</label>
                                <select name="estado" class="form-control">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Campos específicos para administrador -->
                        <div id="campos-admin" style="display: none;">
                            <div class="form-group">
                                <label>Rol Administrativo:</label>
                                <input type="text" name="rolAdmin" class="form-control" placeholder="Ej: Director, Coordinador">
                            </div>
                        </div>
                        
                        <!-- Campos específicos para estudiante -->
                        <div id="campos-estudiante" style="display: none;">
                            <div class="form-group">
                                <label>Fecha de Nacimiento:</label>
                                <input type="date" name="fechaNac" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Agregar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarCamposEspecificos(tipo) {
            // Ocultar todos los campos específicos
            document.getElementById('campos-docente').style.display = 'none';
            document.getElementById('campos-admin').style.display = 'none';
            document.getElementById('campos-estudiante').style.display = 'none';
            
            // Mostrar campos según el tipo seleccionado
            if (tipo === 'docente') {
                document.getElementById('campos-docente').style.display = 'block';
            } else if (tipo === 'admin') {
                document.getElementById('campos-admin').style.display = 'block';
            } else if (tipo === 'estudiante') {
                document.getElementById('campos-estudiante').style.display = 'block';
            }
        }
        
        function editarUsuario(cedula) {
            // Redirigir a página de edición con la cédula
            window.location.href = 'adm_usr/editar_usr.php?cedula=' + cedula;
        }
        
        function eliminarUsuario(cedula) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
                // Crear un formulario temporal para enviar la petición POST
                var form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                var inputAccion = document.createElement('input');
                inputAccion.type = 'hidden';
                inputAccion.name = 'accion';
                inputAccion.value = 'eliminar';
                
                var inputCedula = document.createElement('input');
                inputCedula.type = 'hidden';
                inputCedula.name = 'cedula';
                inputCedula.value = cedula;
                
                form.appendChild(inputAccion);
                form.appendChild(inputCedula);
                document.body.appendChild(form);
                
                form.submit();
            }
        }
        
        function cambiarEstado(cedula, nuevoEstado) {
            if (confirm('¿Deseas cambiar el estado del docente a ' + nuevoEstado + '?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                var inputAccion = document.createElement('input');
                inputAccion.type = 'hidden';
                inputAccion.name = 'accion';
                inputAccion.value = 'cambiar_estado';
                
                var inputCedula = document.createElement('input');
                inputCedula.type = 'hidden';
                inputCedula.name = 'cedula';
                inputCedula.value = cedula;
                
                var inputEstado = document.createElement('input');
                inputEstado.type = 'hidden';
                inputEstado.name = 'nuevo_estado';
                inputEstado.value = nuevoEstado;
                
                form.appendChild(inputAccion);
                form.appendChild(inputCedula);
                form.appendChild(inputEstado);
                document.body.appendChild(form);
                
                form.submit();
            }
        }
        
        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

</body>
</html>