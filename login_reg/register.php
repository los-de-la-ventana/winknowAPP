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
    // Rellenar a 7 dígitos con ceros a la izquierda
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
    
    // Obtener el último dígito (dígito verificador)
    $validationDigit = (int)substr($ci, -1);
    
    // Obtener los primeros 6 o 7 dígitos (sin el dígito verificador)
    $ciWithoutCheck = substr($ci, 0, -1);
    
    // Calcular el dígito verificador esperado
    $expectedDigit = validation_digit($ciWithoutCheck);
    
    return $validationDigit === $expectedDigit;
}

$mysqli = conectarDB();

// Variables para mensajes
$mensaje = '';
$tipo_mensaje = '';
$redirigir = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tipo = $_POST['operacion'] ?? '';
        $cedula_raw = ($_POST['cedula'] ?? '');
        $nombre = ($_POST['nombre'] ?? '');
        $telefono = ($_POST['telefono'] ?? '');
        $contra = $_POST['contra'] ?? '';

        // Validaciones básicas
        if (empty($tipo) || empty($cedula_raw) || empty($nombre) || empty($contra)) {
            throw new Exception("Todos los campos son obligatorios");
        }
        
        // Validar cédula uruguaya
        if (!validate_ci($cedula_raw)) {
            throw new Exception("La cédula ingresada no es válida");
        }
        
        // Limpiar y convertir a entero
        $cedula = intval(clean_ci($cedula_raw));
        $pass = password_hash($contra, PASSWORD_BCRYPT);
        
        // Verificar si el usuario ya existe
        $checkUsuario = $mysqli->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
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
        
        // Insertar en tabla usuarios
        $stmtUsuario = $mysqli->prepare("INSERT INTO usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
        if (!$stmtUsuario) {
            throw new Exception("Error preparando consulta de usuario: " . $mysqli->error);
        }
        
        $stmtUsuario->bind_param("iss", $cedula, $pass, $nombre);
        if (!$stmtUsuario->execute()) {
            throw new Exception("Error al crear usuario: " . $stmtUsuario->error);
        }
        $stmtUsuario->close();
        
        // Insertar en tabla email
        $stmtEmail = $mysqli->prepare("INSERT INTO email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
        if (!$stmtEmail) {
            throw new Exception("Error preparando consulta de email: " . $mysqli->error);
        }
        
        $email_empty = '';
        $stmtEmail->bind_param("iss", $cedula, $telefono, $email_empty);
        if (!$stmtEmail->execute()) {
            throw new Exception("Error al crear email: " . $stmtEmail->error);
        }
        $stmtEmail->close();
        
        // Manejar tipos específicos de usuario
        if ($tipo === 'admin') {
            $rolAdm = trim($_POST['rolAdm'] ?? '');
            if (empty($rolAdm)) {
                throw new Exception("El rol de administrador es obligatorio");
            }
            
            $stmtAdmin = $mysqli->prepare("INSERT INTO administrador (Cedula, rolAdmin) VALUES (?, ?)");
            if (!$stmtAdmin) {
                throw new Exception("Error preparando consulta de administrador: " . $mysqli->error);
            }
            
            $stmtAdmin->bind_param("is", $cedula, $rolAdm);
            if (!$stmtAdmin->execute()) {
                throw new Exception("Error al registrar administrador: " . $stmtAdmin->error);
            }
            $stmtAdmin->close();
            
            $mysqli->commit();
            $mensaje = "Registro exitoso como Administrador";
            $tipo_mensaje = 'success';
            
        } elseif ($tipo === 'docente') {
            
            $stmtDocente = $mysqli->prepare("INSERT INTO docente (Cedula, contrasenia) VALUES (?, ?)");
            if (!$stmtDocente) {
                throw new Exception("Error preparando consulta de docente: " . $mysqli->error);
            }

            $stmtDocente->bind_param("is", $cedula, $pass);
            if (!$stmtDocente->execute()) {
                throw new Exception("Error al registrar docente: " . $stmtDocente->error);
            }
            $stmtDocente->close();
            
            $mysqli->commit();
            $mensaje = "Registro exitoso como Docente";
            $tipo_mensaje = 'success';
            
        } elseif ($tipo === 'estudiante') {
            $stmtEstudiante = $mysqli->prepare("INSERT INTO estudiante (Cedula) VALUES (?)");
            if (!$stmtEstudiante) {
                throw new Exception("Error preparando consulta de estudiante: " . $mysqli->error);
            }
            
            $stmtEstudiante->bind_param("i", $cedula);
            if (!$stmtEstudiante->execute()) {
                throw new Exception("Error al registrar estudiante: " . $stmtEstudiante->error);
            }
            $stmtEstudiante->close();
            
            $mysqli->commit();
            $mensaje = "Registro exitoso como Estudiante";
            $tipo_mensaje = 'success';
            
        } else {
            throw new Exception("Tipo de usuario no válido");
        }
        
        // Redirigir a usuarios.php después de cualquier registro exitoso
        if ($tipo_mensaje === 'success') {
            $redirigir = '../admin/usuarios.php';
        }
        
    } catch (Exception $e) {
        // Rollback en caso de error
        $mysqli->rollback();
        $mensaje = $e->getMessage();
        $tipo_mensaje = 'error';
    } finally {
        $mysqli->autocommit(TRUE);
    }
}

$mysqli->close();

// Si hay redirección exitosa, hacerla con header
if ($tipo_mensaje === 'success' && !empty($redirigir)) {
    header("Location: $redirigir");
    exit;
}

include '../front/usrreg_form.php';
?>