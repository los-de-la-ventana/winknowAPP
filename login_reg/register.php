<?php
session_start();
require("../conexion.php");
require("../conexionSERVER.php");


// Validador de cédula uruguaya corregido
class CiValidator
{
    /**
     * @param string $ci
     * @return bool
     */
    public function validate_ci(string $ci): bool
    {
        if (empty(trim($ci))) {
            return false;
        }
        
        $ci = $this->clean_ci($ci);
        
        if (strlen($ci) < 7 || strlen($ci) > 8) {
            return false;
        }
        
        // Obtener el último dígito (dígito verificador)
        $validationDigit = (int)substr($ci, -1);
        
        // Obtener los primeros 6 o 7 dígitos (sin el dígito verificador)
        $ciWithoutCheck = substr($ci, 0, -1);
        
        // Calcular el dígito verificador esperado
        $expectedDigit = $this->validation_digit($ciWithoutCheck);
        
        return $validationDigit === $expectedDigit;
    }

    /**
     * @param string $ci
     * @return string
     */
    public function clean_ci(string $ci): string
    {
        return preg_replace('/\D/', '', $ci);
    }

    /**
     * @param string $ci
     * @return int
     */
    public function validation_digit(string $ci): int
    {
        $ci = $this->clean_ci($ci);
        // Pad to 7 digits with leading zeros
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
    
   
}

$mysqli = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo     = $_POST['operacion'];
    $cedula_raw = trim($_POST['cedula']);
    $nombre   = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $contra   = $_POST['contra'];
    $pass     = password_hash($contra, PASSWORD_BCRYPT);
    
    // Validar cédula uruguaya
    $validator = new CiValidator();
    
  
    
    // Limpiar y convertir a entero
    $cedula = intval($validator->clean_ci($cedula_raw));
    
    // Debug temporal - remover después de probar
    error_log("DEBUG: Cedula recibida = " . $cedula . " (tipo: " . gettype($cedula) . "), CI original: " . $cedula_raw);
    
    $rolYaExiste = false;
    $mensajeError = "";
    
    if ($rolYaExiste) {
        echo "<script>alert('$mensajeError'); window.history.back();</script>";
        exit;
    }
    
    // Check if user already exists using prepared statement
    $checkUsuario = $mysqli->prepare("SELECT Cedula FROM Usuarios WHERE Cedula = ?");
    $checkUsuario->bind_param("i", $cedula);
    $checkUsuario->execute();
    $result = $checkUsuario->get_result();
    $usuarioExiste = $result->num_rows > 0;
    $checkUsuario->close();
    
    if (!$usuarioExiste) {
        // Insert into Usuarios table using prepared statement
        $stmtUsuario = $mysqli->prepare("INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
        $stmtUsuario->bind_param("iss", $cedula, $pass, $nombre);
        if (!$stmtUsuario->execute()) {
            echo "<script>alert('Error al crear usuario: " . $stmtUsuario->error . "'); window.history.back();</script>";
            exit;
        }
        $stmtUsuario->close();
        
        // Insert into Email table using prepared statement
        $stmtEmail = $mysqli->prepare("INSERT INTO Email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
        $email_empty = '';
        $stmtEmail->bind_param("iss", $cedula, $telefono, $email_empty);
        if (!$stmtEmail->execute()) {
            echo "<script>alert('Error al crear email: " . $stmtEmail->error . "'); window.history.back();</script>";
            exit;
        }
        $stmtEmail->close();
    }
    
    // Set session variables
    $_SESSION['cedula']    = $cedula;
    $_SESSION['nombre']    = $nombre;
    $_SESSION['telefono']  = $telefono;
    $_SESSION['tipo']      = $tipo;
    $_SESSION['logged_in'] = true;
    
    if ($tipo === 'admin') {
        $rolAdm = $_POST['rolAdm'];
        $_SESSION['rolAdmin'] = $rolAdm;
        
        // Insert admin using prepared statement
        $stmtAdmin = $mysqli->prepare("INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, TRUE, ?)");
        $stmtAdmin->bind_param("is", $cedula, $rolAdm);
        if ($stmtAdmin->execute()) {
            echo "<script>alert('Registro exitoso como Administrador'); window.location.href='../admin/inicio.php';</script>";
        } else {
            echo "<script>alert('Error al registrar administrador: " . $stmtAdmin->error . "'); window.history.back();</script>";
        }
        $stmtAdmin->close();
        
    } elseif ($tipo === 'docente') {
        $anioIns  = $_POST['anioIns'];
        $estado   = $_POST['estado'];
        $fechaIns = "$anioIns-01-01";
        
        $_SESSION['anioIns'] = $anioIns;
        $_SESSION['estado']  = $estado;
        
        // Insert docente using prepared statement
        $stmtDocente = $mysqli->prepare("INSERT INTO Docente (Cedula, contrasenia, AnioInsercion, Estado) VALUES (?, ?, ?, ?)");
        $stmtDocente->bind_param("isss", $cedula, $pass, $fechaIns, $estado);
        if ($stmtDocente->execute()) {
            echo "<script>alert('Registro exitoso como Docente'); window.location.href='../docente/inicioDoc.php';</script>";
        } else {
            echo "<script>alert('Error al registrar docente: " . $stmtDocente->error . "'); window.history.back();</script>";
        }
        $stmtDocente->close();
        
    } else { // estudiante
        $fnac = $_POST['fnac'];
        $_SESSION['fnac'] = $fnac;
        
        // Insert estudiante using prepared statement
        $stmtEstudiante = $mysqli->prepare("INSERT INTO Estudiante (Cedula, FechaNac) VALUES (?, ?)");
        $stmtEstudiante->bind_param("is", $cedula, $fnac);
        if ($stmtEstudiante->execute()) {
            echo "<script>alert('Registro exitoso como Estudiante'); window.location.href='../estudiante/inicioEst.php';</script>";
        } else {
            echo "<script>alert('Error al registrar estudiante: " . $stmtEstudiante->error . "'); window.history.back();</script>";
        }
        $stmtEstudiante->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/wk_logo.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WinKnow - Registro</title>
    <link rel="stylesheet" href="../inicio.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="register.php">
        <!-- Selección de tipo -->
        <label for="operacion">Seleccione tipo de usuario</label>
        <select name="operacion" id="operacion" required>
            <option value="">-- Seleccione --</option>
            <option value="admin">Administrador</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>
        <br>

        <!-- Bloques por tipo -->
        <div id="admin-form" class="input-field" style="display:none;">
            <input type="text"     name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text"     name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones">
            <input type="tel"      name="telefono" placeholder="Teléfono" required>
            <input type="text"     name="rolAdm" placeholder="Rol admin" required>
        </div>

        <div id="docente-form" class="input-field" style="display:none;">
            <input type="text"     name="nombre"  placeholder="Nombre" required>
            <input type="password" name="contra"  placeholder="Contraseña" required>
            <input type="text"     name="cedula"  placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones">
            <input type="text"     name="estado"  placeholder="Estado" required>
            <input type="tel"      name="telefono" placeholder="Teléfono" required>
            <!-- Added missing anioIns field for docente -->
            <input type="number"   name="anioIns" placeholder="Año de inserción" required min="1900" max="2025">
        </div>

        <div id="estudiante-form" class="input-field" style="display:none;">
            <input type="text"     name="nombre"  placeholder="Nombre" required>
            <input type="password" name="contra"  placeholder="Contraseña" required>
            <input type="text"     name="cedula"  placeholder="Cédula (solo números)" required pattern="[0-9]+" title="Solo números sin puntos ni guiones">
            <input type="date"     name="fnac"    placeholder="Fecha nacimiento" required>
            <input type="tel"      name="telefono" placeholder="Teléfono" required>
        </div>

        <button type="submit">Registrar</button>
        <br>
        <a href="login.php" style="color:white;">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>
</div>

<script src="registerValidation.js"></script>
</body>
</html>