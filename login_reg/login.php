<?php
session_start();
require_once __DIR__ . '/../conexion.php';
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
            echo "<script>alert('Bienvenido, $nombre'); window.location.href='dashboard.php';</script>";
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
  <link rel="icon" type="image/x-icon" href="../img/image-removebg-preview.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WK - Inicio de sesión</title>
  <link rel="stylesheet" href="../inicio.css">
  <link rel="stylesheet" href="../aulas.css">
</head>
<body>
  <div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="login.php">
      <h2>Iniciar Sesión</h2>
      <input type="number" name="cedula" placeholder="Cédula" required>
      <input type="password" name="contra" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
      <a href="register.php" style="color:white;">¿No tienes una cuenta? Regístrate</a>
    </form>
  </div>

  <script src="registerValidation.js"></script>
</body>
</html>