<?php
session_start();
require("../conexion.php");

// Validador de cédula uruguaya
class CiValidator {
    public function validate_ci(string $ci): bool {
        if (empty(trim($ci))) return false;
        $ci = $this->clean_ci($ci);
        if (strlen($ci) < 7 || strlen($ci) > 8) return false;
        $validationDigit = (int)substr($ci, -1);
        $ciWithoutCheck = substr($ci, 0, -1);
        $expectedDigit = $this->validation_digit($ciWithoutCheck);
        return $validationDigit === $expectedDigit;
    }

    public function clean_ci(string $ci): string {
        return preg_replace('/\D/', '', $ci);
    }

    public function validation_digit(string $ci): int {
        $ci = $this->clean_ci($ci);
        $ci = str_pad($ci, 7, '0', STR_PAD_LEFT);
        $sum = 0;
        $baseNumber = "2987634";
        for ($i = 0; $i < 7; $i++) {
            $sum += ((int)$ci[$i] * (int)$baseNumber[$i]) % 10;
        }
        $remainder = $sum % 10;
        return $remainder === 0 ? 0 : 10 - $remainder;
    }
}

$mysqli = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo      = $_POST['operacion'] ?? '';
    $cedulaRaw = trim($_POST['cedula']);
    $nombre    = trim($_POST['nombre']);
    $telefono  = trim($_POST['telefono']);
    $contra    = $_POST['contra'];
    $pass      = password_hash($contra, PASSWORD_BCRYPT);

    $validator = new CiValidator();
    $cedula = intval($validator->clean_ci($cedulaRaw));

    if (!$validator->validate_ci($cedulaRaw)) {
        echo "<script>alert('Cédula inválida'); window.history.back();</script>";
        exit;
    }

    // Verificar si usuario ya existe
    $stmtCheck = $mysqli->prepare("SELECT Cedula FROM Usuarios WHERE Cedula = ?");
    $stmtCheck->bind_param("i", $cedula);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    if($result->num_rows > 0){
        echo "<script>alert('Usuario ya existe'); window.history.back();</script>";
        exit;
    }
    $stmtCheck->close();

    // Insert en tabla Usuarios con tipo_usuario
    $stmtUser = $mysqli->prepare("INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr, tipo_usuario) VALUES (?, ?, ?, ?)");
    $stmtUser->bind_param("isss", $cedula, $pass, $nombre, $tipo);
    if(!$stmtUser->execute()){
        echo "<script>alert('Error al crear usuario: ".$stmtUser->error."'); window.history.back();</script>";
        exit;
    }
    $stmtUser->close();

    // Insert en Email
    $stmtEmail = $mysqli->prepare("INSERT INTO Email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
    $email_empty = '';
    $stmtEmail->bind_param("iss", $cedula, $telefono, $email_empty);
    $stmtEmail->execute();
    $stmtEmail->close();

    $_SESSION['cedula']    = $cedula;
    $_SESSION['nombre']    = $nombre;
    $_SESSION['telefono']  = $telefono;
    $_SESSION['tipo']      = $tipo;
    $_SESSION['logged_in'] = true;

    // Insert según tipo
    if($tipo === 'admin'){
        $rolAdm = $_POST['rolAdm'];
        $_SESSION['rolAdmin'] = $rolAdm;
        $stmtAdmin = $mysqli->prepare("INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, TRUE, ?)");
        $stmtAdmin->bind_param("is", $cedula, $rolAdm);
        $stmtAdmin->execute();
        $stmtAdmin->close();
        echo "<script>alert('Registro exitoso como Administrador'); window.location.href='../admin/inicio.php';</script>";
    }
    elseif($tipo === 'docente'){
        $anioIns  = $_POST['anioIns'];
        $estado   = $_POST['estado'];
        $_SESSION['anioIns'] = $anioIns;
        $_SESSION['estado']  = $estado;
        $fechaIns = "$anioIns-01-01";
        $stmtDoc = $mysqli->prepare("INSERT INTO Docente (Cedula, contrasenia, AnioInsercion, Estado) VALUES (?, ?, ?, ?)");
        $stmtDoc->bind_param("isss", $cedula, $pass, $fechaIns, $estado);
        $stmtDoc->execute();
        $stmtDoc->close();
        echo "<script>alert('Registro exitoso como Docente'); window.location.href='../docente/inicioDoc.php';</script>";
    }
    else{ // estudiante
        $fnac = $_POST['fnac'];
        $_SESSION['fnac'] = $fnac;
        $stmtEst = $mysqli->prepare("INSERT INTO Estudiante (Cedula, FechaNac) VALUES (?, ?)");
        $stmtEst->bind_param("is", $cedula, $fnac);
        $stmtEst->execute();
        $stmtEst->close();
        echo "<script>alert('Registro exitoso como Estudiante'); window.location.href='../estudiante/inicioEst.php';</script>";
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
<link rel="stylesheet" href="titleFX.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<header>
<button id="toggle-theme" class="boton-primario">Cambiar tema</button>
<script src="../lightmode.js"></script>
</header>

<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="register.php">
        <p class="cursor typewriter-animation">REGÍSTRATE</p>
        <label for="operacion">Seleccione tipo de usuario</label>
        <select name="operacion" id="operacion" required>
            <option value="">-- Seleccione --</option>
            <option value="admin">Administrador</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>

        <div id="admin-form" class="input-field" style="display:none;">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text" name="cedula" placeholder="Cédula (solo números)" required pattern="[0-9]+">
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="text" name="rolAdm" placeholder="Rol admin" required>
        </div>

        <div id="docente-form" class="input-field" style="display:none;">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text" name="cedula" placeholder="Cédula" required pattern="[0-9]+">
            <input type="text" name="estado" placeholder="Estado" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="number" name="anioIns" placeholder="Año de inserción" min="1900" max="2025" required>
        </div>

        <div id="estudiante-form" class="input-field" style="display:none;">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <input type="text" name="cedula" placeholder="Cédula" required pattern="[0-9]+">
            <input type="date" name="fnac" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
        </div>

        <button type="submit">Registrar</button>
        <a href="login.php" class="link">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>
</div>

<script>
// Mostrar el formulario según tipo y deshabilitar campos ocultos
function mostrarForm(tipo){
    ['admin','docente','estudiante'].forEach(t=>{
        let div = document.getElementById(t+'-form');
        if(t===tipo){
            div.style.display='block';
            div.querySelectorAll('input, select').forEach(i=>i.disabled=false);
        } else {
            div.style.display='none';
            div.querySelectorAll('input, select').forEach(i=>i.disabled=true);
        }
    });
}
document.getElementById('operacion').addEventListener('change', e=>{
    mostrarForm(e.target.value);
});
</script>
</body>
</html>
