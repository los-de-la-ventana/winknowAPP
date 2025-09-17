<?php
session_start();
require_once __DIR__ . '/conexion.php';
$mysqli = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo     = $_POST['operacion'];
    $cedula   = $_POST['cedula'];
    $nombre   = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $contra   = $_POST['contra'];

    $pass = password_hash($contra, PASSWORD_BCRYPT);
if (empty($cedula) || strlen($cedula) !== 8) {
    echo "<script>alert('Cédula inválida o vacía.'); window.history.back();</script>";
    $mysqli->close();
    exit;
}
    // Verificar si la cédula ya existe
    $check = $mysqli->prepare("SELECT Cedula FROM Usuarios WHERE Cedula = ?");
    $check->bind_param("s", $cedula);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('La cédula ya está registrada. Intente con otra.'); window.history.back();</script>";
        $check->close();
        $mysqli->close();
        exit;
    }
    $check->close();

    // Insertar en Usuarios
    $sql = "INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $cedula, $pass, $nombre);
    $stmt->execute() or die("<script>alert('Error Usuarios: {$stmt->error}'); window.history.back();</script>");
    $stmt->close();

    // Insertar en Email
    $sql = "INSERT INTO Email (Cedula, numeroTelefono, email) VALUES (?, ?, '')";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $cedula, $telefono);
    $stmt->execute() or die("<script>alert('Error Email: {$stmt->error}'); window.history.back();</script>");
    $stmt->close();

    // Guardar datos comunes en sesión
    $_SESSION['cedula']   = $cedula;
    $_SESSION['nombre']   = $nombre;
    $_SESSION['telefono'] = $telefono;
    $_SESSION['tipo']     = $tipo;

    // Insertar en tabla específica y guardar en sesión
    if ($tipo === 'admin') {
        $rolAdm = $_POST['rolAdm'];
        $_SESSION['rolAdmin'] = $rolAdm;

        $sql = "INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, 1, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $cedula, $rolAdm);
    }
    elseif ($tipo === 'docente') {
        $anioIns  = $_POST['anioIns'];
        $estado   = $_POST['estado'];
        $fechaIns = "$anioIns-01-01";

        $_SESSION['anioIns'] = $anioIns;
        $_SESSION['estado']  = $estado;

        $sql = "INSERT INTO Docente (Cedula, grado, contrasenia, AnioInsercion, Estado)
                VALUES (?, 0, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $cedula, $pass, $fechaIns, $estado);
    }
    else { // estudiante
        $fnac = $_POST['fnac'];
        $_SESSION['fnac'] = $fnac;

        $sql = "INSERT INTO Estudiante (Cedula, FechaNac) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $cedula, $fnac);
    }

    $stmt->execute() or die("<script>alert('Error {$tipo}: {$stmt->error}'); window.history.back();</script>");
    $stmt->close();

    // Redirección por tipo
    switch ($tipo) {
        case 'admin':
            echo "<script>alert('Registro exitoso como Administrador'); window.location.href='admin/inicioAdmin.php';</script>";
            break;
        case 'docente':
            echo "<script>alert('Registro exitoso como Docente'); window.location.href='docente/inicioDoc.php';</script>";
            break;
        default: // estudiante
            echo "<script>alert('Registro exitoso como Estudiante'); window.location.href='estudiante/inicioEst.php';</script>";
            break;
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="/img/image-removebg-preview (2).png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WinKnow - Registro</title>
  <link rel="stylesheet" href="inicio.css">
  <link rel="stylesheet" href="aulas.css">
</head>
<body>

  <div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="register.php">
      <!-- Selección de tipo -->
      <label for="operacion">Seleccione tipo de usuario</label>
      <select name="operacion" id="operacion" required>
        <option value="admin">Administrador</option>
        <option value="docente">Docente</option>
        <option value="estudiante">Estudiante</option>
      </select>

      <!-- Bloques por tipo -->
      <div id="admin-form" class="input-field" style="display:none;">
        <input type="text"     name="nombre" placeholder="Nombre">
        <input type="password" name="contra" placeholder="Contraseña">
        <input type="number"   name="cedula" placeholder="Cédula">
        <input type="tel"      name="telefono" placeholder="Teléfono">
        <input type="text"     name="rolAdm" placeholder="Rol admin">
      </div>

      <div id="docente-form" class="input-field" style="display:none;">
        <input type="text"     name="nombre"  placeholder="Nombre">
        <input type="password" name="contra"  placeholder="Contraseña">
        <input type="number"   name="cedula"  placeholder="Cédula">
        <input type="number"   name="anioIns" placeholder="Año ins.">
        <input type="text"     name="estado"  placeholder="Estado">
        <input type="tel"      name="telefono" placeholder="Teléfono">
      </div>

      <div id="estudiante-form" class="input-field" style="display:none;">
        <input type="text"     name="nombre"  placeholder="Nombre">
        <input type="password" name="contra"  placeholder="Contraseña">
        <input type="number"   name="cedula"  placeholder="Cédula">
        <input type="date"     name="fnac"    placeholder="Fecha nac.">
        <input type="tel"      name="telefono" placeholder="Teléfono">
      </div>

      <button type="submit">Enviar</button>
    </form>
  </div>

  <!-- JS al final para que el DOM ya exista -->
  <script src="registerValidation.js"></script>
</body>
</html>
