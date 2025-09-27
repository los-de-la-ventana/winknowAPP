<?php
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// Variables para mensajes
$mensaje = '';
$tipo_mensaje = '';

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
            $queryAdmin = $mysqli->prepare("SELECT rolAdmin FROM Administrador WHERE Cedula = ?");
            $queryAdmin->bind_param("s", $cedula);
            $queryAdmin->execute();
            $resultAdmin = $queryAdmin->get_result();
            if ($resultAdmin->num_rows > 0) {
                $data = $resultAdmin->fetch_assoc();
                $_SESSION['rolAdmin'] = $data['rolAdmin'];
                $_SESSION['tipo'] = 'admin';
                $_SESSION['logged_in'] = true;
                
                // Redirigir con mensaje de éxito
                header("Location: ../admin/inicio.php");
                exit;
            } else {
                $queryDocente = $mysqli->prepare("SELECT Estado FROM Docente WHERE Cedula = ?");
                $queryDocente->bind_param("s", $cedula);
                $queryDocente->execute();
                $resultDocente = $queryDocente->get_result();
                if ($resultDocente->num_rows > 0) {
                    $data = $resultDocente->fetch_assoc();
                    $_SESSION['estado'] = $data['Estado'];
                    $_SESSION['tipo'] = 'docente';
                    $_SESSION['logged_in'] = true;
                    
                    // Redirigir con mensaje de éxito
                    header("Location: ../docente/inicioDoc.php");
                    exit;
                } else {
                    $queryEst = $mysqli->prepare("SELECT FechaNac FROM Estudiante WHERE Cedula = ?");
                    $queryEst->bind_param("s", $cedula);
                    $queryEst->execute();
                    $resultEst = $queryEst->get_result();
                    if ($resultEst->num_rows > 0) {
                        $data = $resultEst->fetch_assoc();
                        $_SESSION['fnac'] = $data['FechaNac'];
                        $_SESSION['tipo'] = 'estudiante';
                        $_SESSION['logged_in'] = true;
                        
                        // Redirigir con mensaje de éxito
                        header("Location: ../estudiante/inicioEst.php");
                        exit;
                    } else {
                        // Si no tiene rol asignado
                        $mensaje = 'Usuario sin rol asignado';
                        $tipo_mensaje = 'error';
                    }
                }
            }
        } else {
            $mensaje = 'Contraseña incorrecta';
            $tipo_mensaje = 'error';
        }
    } else {
        $mensaje = 'Usuario no encontrado';
        $tipo_mensaje = 'error';
    }

    $stmt->close();
    $mysqli->close();
}

include '../headerfooter/header.html';
?>

<link rel="stylesheet" href="titleFX.css">
<title>WinKnow - Login</title>

<div class="form-overlay">
    <form id="registroForm" class="form-flotante" method="post" action="login.php">
        <p class="cursor typewriter-animation">INICIA SESION</p>
        
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>" style="
                padding: 10px; 
                margin: 10px 0; 
                border-radius: 5px; 
                text-align: center;
                <?php echo $tipo_mensaje === 'error' ? 'background-color: #ffebee; color: #c62828; border: 1px solid #ef5350;' : 'background-color: #e8f5e8; color: #2e7d32; border: 1px solid #66bb6a;'; ?>
            ">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <input type="number" name="cedula" placeholder="Cédula" required>
        <input type="password" name="contra" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
        <a href="register.php" class="link">¿No tienes una cuenta? Regístrate</a>
    </form>
</div>

<script src="registerValidation.js"></script>

</body>
</html>