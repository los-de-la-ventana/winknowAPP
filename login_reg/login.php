<?php
session_start();
require("../conexion.php");
$mysqli = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $contra = $_POST['contra'];

    $sql = "SELECT Contrasenia, Nombre_usr FROM Usuarios WHERE Cedula = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hash, $nombre);
        $stmt->fetch();

        if (password_verify($contra, $hash)) {
            $_SESSION['cedula'] = $cedula;
            $_SESSION['nombre'] = $nombre;

            // Verificar rol
            $rol = '';
            $queryAdmin = $mysqli->prepare("SELECT rolAdmin FROM Administrador WHERE Cedula = ?");
            $queryAdmin->bind_param("s", $cedula);
            $queryAdmin->execute();
            $resultAdmin = $queryAdmin->get_result();
            if ($resultAdmin->num_rows > 0) {
                $data = $resultAdmin->fetch_assoc();
                $_SESSION['rolAdmin'] = $data['rolAdmin'];
                $_SESSION['tipo'] = 'admin';
                $_SESSION['logged_in'] = true;
                echo "<script>alert('Bienvenido, $nombre'); window.location.href='../admin/inicio.php';</script>";
                exit;
            }

            $queryDocente = $mysqli->prepare("SELECT Estado FROM Docente WHERE Cedula = ?");
            $queryDocente->bind_param("s", $cedula);
            $queryDocente->execute();
            $resultDocente = $queryDocente->get_result();
            if ($resultDocente->num_rows > 0) {
                $data = $resultDocente->fetch_assoc();
                $_SESSION['estado'] = $data['Estado'];
                $_SESSION['tipo'] = 'docente';
                $_SESSION['logged_in'] = true;
                echo "<script>alert('Bienvenido, $nombre'); window.location.href='../docente/inicioDoc.php';</script>";
                exit;
            }

            $queryEst = $mysqli->prepare("SELECT FechaNac FROM Estudiante WHERE Cedula = ?");
            $queryEst->bind_param("s", $cedula);
            $queryEst->execute();
            $resultEst = $queryEst->get_result();
            if ($resultEst->num_rows > 0) {
                $data = $resultEst->fetch_assoc();
                $_SESSION['fnac'] = $data['FechaNac'];
                $_SESSION['tipo'] = 'estudiante';
                $_SESSION['logged_in'] = true;
                echo "<script>alert('Bienvenido, $nombre'); window.location.href='../estudiante/inicioEst.php';</script>";
                exit;
            }

            // Si no tiene rol asignado
            echo "<script>alert('Usuario sin rol asignado'); window.history.back();</script>";
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
    }

    $stmt->close();
    $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/wk_logo.ico">
  <link rel="icon" type="image/x-icon" href="../img/image-removebg-preview.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WK - Inicio de sesión</title>
  <link rel="stylesheet" href="../inicio.css">
  <link rel="stylesheet" href="titleFX.css">
</head>
<body>


  <div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="login.php">
    <p class="cursor typewriter-animation">INICIA SESION</p>
      <input type="number" name="cedula" placeholder="Cédula" required>
      <input type="password" name="contra"  placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
      <a href="register.php" style="color:white;">¿No tienes una cuenta? Regístrate</a>
    </form>
  </div>

  <script src="registerValidation.js"></script>

</body>
</html>