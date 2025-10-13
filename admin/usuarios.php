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
            
        default:
            $mensaje = 'Acción no válida';
            $tipo_mensaje = 'danger';
    }
}

$usuarios = listarUsuarios()['data'] ?? [];
$estadisticas = obtenerEstadisticasUsuarios()['data'] ?? [];

include '../front/header.html';
include '../front/gestion_usuarios.php';

?>
<script src="adminValidation.js"></script>
