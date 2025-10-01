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
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
    
    if ($resultado['success']) {
        $resultado_usuario = obtenerUsuario($cedula);
        $usuario_datos = $resultado_usuario['data'];
    }
}
include '../../front/header.html';
?>

<link rel="stylesheet" href="../../login_reg/titleFX.css">

<title>WinKnow - Editar Usuario</title>

<?php include '../../front/navADM.php'; ?>

<div class="form-overlay"
     data-mensaje="<?php echo htmlspecialchars($mensaje, ENT_QUOTES); ?>"
     data-tipo="<?php echo htmlspecialchars($tipo_mensaje, ENT_QUOTES); ?>">
     
    <form id="editarUsuarioForm" class="form-flotante" method="POST">
        <p class="cursor typewriter-animation">EDITAR USUARIO</p>
        
        <div style="text-align: center; margin-bottom: 15px;">
            <strong>Cédula:</strong> <?php echo htmlspecialchars($usuario_datos['Cedula']); ?>
            <br>
            <span style="background: #555; padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                <?php echo htmlspecialchars($usuario_datos['tipo_usuario']); ?>
            </span>
        </div>
        
        <input type="text" name="nombre" placeholder="Nombre Completo" 
               value="<?php echo htmlspecialchars($usuario_datos['Nombre_usr'] ?? ''); ?>" required>
        
        <input type="email" name="email" placeholder="Email" 
               value="<?php echo htmlspecialchars($usuario_datos['email'] ?? ''); ?>">
        
        <input type="text" name="telefono" placeholder="Teléfono" 
               value="<?php echo htmlspecialchars($usuario_datos['numeroTelefono'] ?? ''); ?>">
        
        <input type="password" name="nueva_contrasenia" placeholder="Nueva Contraseña (opcional)">
        
        <?php if ($usuario_datos['tipo_usuario'] === 'Docente'): ?>
            <select name="estado_docente" required>
                <option value="Activo" <?php echo ($usuario_datos['estado_docente'] === 'Activo') ? 'selected' : ''; ?>>
                    Activo
                </option>
                <option value="Inactivo" <?php echo ($usuario_datos['estado_docente'] === 'Inactivo') ? 'selected' : ''; ?>>
                    Inactivo
                </option>
            </select>
            
        <?php elseif ($usuario_datos['tipo_usuario'] === 'Administrador'): ?>
            <input type="text" name="rolAdmin" placeholder="Rol Administrativo" 
                   value="<?php echo htmlspecialchars($usuario_datos['rolAdmin'] ?? ''); ?>">
            
        <?php elseif ($usuario_datos['tipo_usuario'] === 'Estudiante'): ?>
            <input type="date" name="fechaNac" placeholder="Fecha de Nacimiento" 
                   value="<?php echo $usuario_datos['FechaNac'] ?? ''; ?>">
        <?php endif; ?>
        
        <button type="submit">Guardar Cambios</button>
        <a href="../usuarios.php" class="link">Volver</a>
    </form>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="editar_usr.js"></script>

</body>
</html>
