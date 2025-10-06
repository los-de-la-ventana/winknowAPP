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
    $_SESSION['tipo_mensaje'] = 'error';
    header("Location: ../usuarios.php");
    exit;
}

$resultado_usuario = obtenerUsuario($cedula);
if (!$resultado_usuario['success']) {
    $_SESSION['mensaje'] = 'Usuario no encontrado';
    $_SESSION['tipo_mensaje'] = 'error';
    header("Location: ../usuarios.php");
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
    $tipo_mensaje = $resultado['success'] ? 'exito' : 'error';
    
    if ($resultado['success']) {
        $resultado_usuario = obtenerUsuario($cedula);
        $usuario_datos = $resultado_usuario['data'];
    }
}

include '../../front/header.html';
include '../../front/editar_usuario_html.php';
?>