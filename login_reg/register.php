<?php
session_start();
require_once __DIR__ . '/../conexion.php';
$mysqli = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo     = $_POST['operacion'];
    $cedula   = $_POST['cedula'];
    $nombre   = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $contra   = $_POST['contra'];
    $pass     = password_hash($contra, PASSWORD_BCRYPT);

    $rolYaExiste = false;
    $mensajeError = "";

    if ($rolYaExiste) {
        echo "<script>alert('$mensajeError'); window.history.back();</script>";
        exit;
    }

    $checkUsuario = mysqli_query($mysqli, "SELECT Cedula FROM Usuarios WHERE Cedula = '$cedula'");
    $usuarioExiste = mysqli_num_rows($checkUsuario) > 0;

    if (!$usuarioExiste) {
        $sqlUsuario = "INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr) VALUES ('$cedula', '$pass', '$nombre')";
        if (!mysqli_query($mysqli, $sqlUsuario)) {
            echo "<script>alert('Error al crear usuario: " . mysqli_error($mysqli) . "'); window.history.back();</script>";
            exit;
        }

        $sqlEmail = "INSERT INTO Email (Cedula, numeroTelefono, email) VALUES ('$cedula', '$telefono', '')";
        if (!mysqli_query($mysqli, $sqlEmail)) {
            echo "<script>alert('Error al crear email: " . mysqli_error($mysqli) . "'); window.history.back();</script>";
            exit;
        }
    }

    $_SESSION['cedula']    = $cedula;
    $_SESSION['nombre']    = $nombre;
    $_SESSION['telefono']  = $telefono;
    $_SESSION['tipo']      = $tipo;
    $_SESSION['logged_in'] = true;

    if ($tipo === 'admin') {
        $rolAdm = $_POST['rolAdm'];
        $_SESSION['rolAdmin'] = $rolAdm;

        $sqlAdmin = "INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES ('$cedula', 1, '$rolAdm')";
        if (mysqli_query($mysqli, $sqlAdmin)) {
            echo "<script>alert('Registro exitoso como Administrador'); window.location.href='../admin/inicio.php';</script>";
        } else {
            echo "<script>alert('Error al registrar administrador: " . mysqli_error($mysqli) . "'); window.history.back();</script>";
        }

    } elseif ($tipo === 'docente') {
        $anioIns  = $_POST['anioIns'];
        $estado   = $_POST['estado'];
        $fechaIns = "$anioIns-01-01";

        $_SESSION['anioIns'] = $anioIns;
        $_SESSION['estado']  = $estado;

        $sqlDocente = "INSERT INTO Docente (Cedula, grado, contrasenia, AnioInsercion, Estado) VALUES ('$cedula', 0, '$pass', '$fechaIns', '$estado')";
        if (mysqli_query($mysqli, $sqlDocente)) {
            echo "<script>alert('Registro exitoso como Docente'); window.location.href='../docente/inicioDoc.php';</script>";
        } else {
            echo "<script>alert('Error al registrar docente: " . mysqli_error($mysqli) . "'); window.history.back();</script>";
        }

    } else {
        $fnac = $_POST['fnac'];
        $_SESSION['fnac'] = $fnac;

        $sqlEstudiante = "INSERT INTO Estudiante (Cedula, FechaNac) VALUES ('$cedula', '$fnac')";
        if (mysqli_query($mysqli, $sqlEstudiante)) {
            echo "<script>alert('Registro exitoso como Estudiante'); window.location.href='../estudiante/inicioEst.php';</script>";
        } else {
            echo "<script>alert('Error al registrar estudiante: " . mysqli_error($mysqli) . "'); window.history.back();</script>";
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="../img/image-removebg-preview.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WinKnow - Registro</title>
  <link rel="stylesheet" href="../inicio.css">
  <link rel="stylesheet" href="../aulas.css">
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
        <input type="number"   name="cedula" placeholder="Cédula" required>
        <input type="tel"      name="telefono" placeholder="Teléfono" required>
        <input type="text"     name="rolAdm" placeholder="Rol admin" required>
      </div>

      <div id="docente-form" class="input-field" style="display:none;">
        <input type="text"     name="nombre"  placeholder="Nombre" required>
        <input type="password" name="contra"  placeholder="Contraseña" required>
        <input type="number"   name="cedula"  placeholder="Cédula" required>
        <input type="number"   name="anioIns" placeholder="Año inserción" required>
        <input type="text"     name="estado"  placeholder="Estado" required>
        <input type="tel"      name="telefono" placeholder="Teléfono" required>
      </div>

      <div id="estudiante-form" class="input-field" style="display:none;">
        <input type="text"     name="nombre"  placeholder="Nombre" required>
        <input type="password" name="contra"  placeholder="Contraseña" required>
        <input type="number"   name="cedula"  placeholder="Cédula" required>
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