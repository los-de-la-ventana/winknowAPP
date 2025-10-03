<?php
session_start();
require("../conexion.php");

// Función para limpiar cédula
function clean_ci($ci) {
    return preg_replace('/\D/', '', $ci);
}

// Función para calcular dígito verificador
function validation_digit($ci) {
    $ci = clean_ci($ci);
    $ci = str_pad($ci, 7, '0', STR_PAD_LEFT);
    
    $sum = 0;
    $baseNumber = "2987634";
    
    for ($i = 0; $i < 7; $i++) {
        $baseDigit = (int)$baseNumber[$i];
        $ciDigit = (int)$ci[$i];
        $sum += ($baseDigit * $ciDigit) % 10;
    }
    
    $remainder = $sum % 10;
    return $remainder === 0 ? 0 : 10 - $remainder;
}

// Función para validar cédula uruguaya
function validate_ci($ci) {
    if (empty(trim($ci))) {
        return false;
    }
    
    $ci = clean_ci($ci);
    
    if (strlen($ci) < 7 || strlen($ci) > 8) {
        return false;
    }
    
    $validationDigit = (int)substr($ci, -1);
    $ciWithoutCheck = substr($ci, 0, -1);
    $expectedDigit = validation_digit($ciWithoutCheck);
    
    return $validationDigit === $expectedDigit;
}

$mysqli = conectarDB();

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cedula_raw = $_POST['cedula'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $contra = $_POST['contra'] ?? '';
        $rolAdm = trim($_POST['rolAdm'] ?? '');

        // Validaciones básicas
        if (empty($cedula_raw) || empty($nombre) || empty($telefono) || empty($contra) || empty($rolAdm)) {
            throw new Exception("Todos los campos son obligatorios");
        }
        
        // Validar cédula uruguaya
        if (!validate_ci($cedula_raw)) {
            throw new Exception("La cédula ingresada no es válida");
        }
        
        $cedula = intval(clean_ci($cedula_raw));
        $pass = password_hash($contra, PASSWORD_BCRYPT);
        
        // Verificar si el usuario ya existe
        $checkUsuario = $mysqli->prepare("SELECT Cedula FROM Usuarios WHERE Cedula = ?");
        if (!$checkUsuario) {
            throw new Exception("Error en la consulta: " . $mysqli->error);
        }
        
        $checkUsuario->bind_param("i", $cedula);
        $checkUsuario->execute();
        $result = $checkUsuario->get_result();
        $usuarioExiste = $result->num_rows > 0;
        $checkUsuario->close();
        
        if ($usuarioExiste) {
            throw new Exception("Ya existe un usuario con esa cédula");
        }
        
        // Comenzar transacción
        $mysqli->autocommit(FALSE);
        
        // Insertar en tabla Usuarios
        $stmtUsuario = $mysqli->prepare("INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
        if (!$stmtUsuario) {
            throw new Exception("Error preparando consulta de usuario: " . $mysqli->error);
        }
        
        $stmtUsuario->bind_param("iss", $cedula, $pass, $nombre);
        if (!$stmtUsuario->execute()) {
            throw new Exception("Error al crear usuario: " . $stmtUsuario->error);
        }
        $stmtUsuario->close();
        
        // Insertar en tabla Email
        $stmtEmail = $mysqli->prepare("INSERT INTO Email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
        if (!$stmtEmail) {
            throw new Exception("Error preparando consulta de email: " . $mysqli->error);
        }
        
        $email_empty = '';
        $stmtEmail->bind_param("iss", $cedula, $telefono, $email_empty);
        if (!$stmtEmail->execute()) {
            throw new Exception("Error al crear email: " . $stmtEmail->error);
        }
        $stmtEmail->close();
        
        // Insertar en tabla Administrador
        $stmtAdmin = $mysqli->prepare("INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, TRUE, ?)");
        if (!$stmtAdmin) {
            throw new Exception("Error preparando consulta de administrador: " . $mysqli->error);
        }
        
        $stmtAdmin->bind_param("is", $cedula, $rolAdm);
        if (!$stmtAdmin->execute()) {
            throw new Exception("Error al registrar administrador: " . $stmtAdmin->error);
        }
        $stmtAdmin->close();
        
        // Establecer variables de sesión
        $_SESSION['cedula'] = $cedula;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['telefono'] = $telefono;
        $_SESSION['tipo'] = 'admin';
        $_SESSION['rolAdmin'] = $rolAdm;
        $_SESSION['logged_in'] = true;
        
        $mysqli->commit();
        $mensaje = "Registro exitoso como Administrador";
        $tipo_mensaje = 'success';
        
        header("Location: ../admin/inicio.php");
        exit;
        
    } catch (Exception $e) {
        $mysqli->rollback();
        $mensaje = $e->getMessage();
        $tipo_mensaje = 'error';
    } finally {
        $mysqli->autocommit(TRUE);
    }
}

$mysqli->close();
include '../front/admRegister_form.php';
?>
