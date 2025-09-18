<?php
session_start();
if (!isset($_SESSION['cedula'])) {
    echo "<script>alert('Debe iniciar sesión primero'); window.location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario</title>
</head>
<body>
  <h2>Hola, <?php echo $_SESSION['nombre']; ?> 👋</h2>
  <p>Tu cédula es: <?php echo $_SESSION['cedula']; ?></p>
  <a href="logout.php">Cerrar sesión</a>
</body>
</html> 