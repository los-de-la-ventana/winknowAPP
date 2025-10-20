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

    $sql = "SELECT Contrasenia, Nombre_usr FROM usuarios WHERE Cedula = ?";
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
            $queryAdmin = $mysqli->prepare("SELECT rolAdmin FROM administrador WHERE Cedula = ?");
            $queryAdmin->bind_param("s", $cedula);
            $queryAdmin->execute();
            $resultAdmin = $queryAdmin->get_result();
            if ($resultAdmin->num_rows > 0) {
                $data = $resultAdmin->fetch_assoc();
                $_SESSION['rolAdmin'] = $data['rolAdmin'];
                $_SESSION['tipo'] = 'admin';
                $_SESSION['logged_in'] = true;
                
                header("Location: ../admin/inicio.php");
                exit;
            } else {
                // Verificar si es docente
                $queryDocente = $mysqli->prepare("SELECT Cedula FROM docente WHERE Cedula = ?");
                $queryDocente->bind_param("s", $cedula);
                $queryDocente->execute();
                $resultDocente = $queryDocente->get_result();
                if ($resultDocente->num_rows > 0) {
                    $_SESSION['tipo'] = 'docente';
                    $_SESSION['logged_in'] = true;
                    
                    header("Location: ../docente/inicioDoc.php");
                    exit;
                } else {
                    // Verificar si es estudiante
                    $queryEst = $mysqli->prepare("SELECT Cedula FROM estudiante WHERE Cedula = ?");
                    $queryEst->bind_param("s", $cedula);
                    $queryEst->execute();
                    $resultEst = $queryEst->get_result();
                    if ($resultEst->num_rows > 0) {
                        $_SESSION['tipo'] = 'estudiante';
                        $_SESSION['logged_in'] = true;
                        
                        header("Location: ../estudiante/inicioEst.php");
                        exit;
                    } else {
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

include '../front/header.html';
include '../front/usrlogin_form.php';
?>