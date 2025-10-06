<?php
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// Verificar que sea un administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// Variables para mensajes
$mensaje = '';
$tipo_mensaje = '';

// PROCESAR REGISTRO DE NUEVO ESPACIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    
    if ($_POST['accion'] === 'registrar') {
        $num_salon = $_POST['num_salon'];
        $capacidad = $_POST['capacidad'];
        $tipo_salon = $_POST['tipo_salon'];
        
        // Validar que no exista el número de salón
        $checkQuery = "SELECT NumSalon FROM Espacios WHERE NumSalon = ?";
        $checkStmt = $mysqli->prepare($checkQuery);
        $checkStmt->bind_param("i", $num_salon);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $mensaje = "El espacio con número $num_salon ya existe";
            $tipo_mensaje = 'error';
        } else {
            // Insertar nuevo espacio
            $insertQuery = "INSERT INTO Espacios (NumSalon, capacidad, Tipo_salon) VALUES (?, ?, ?)";
            $insertStmt = $mysqli->prepare($insertQuery);
            $insertStmt->bind_param("iis", $num_salon, $capacidad, $tipo_salon);
            
            if ($insertStmt->execute()) {
                $mensaje = "Espacio $tipo_salon $num_salon registrado exitosamente";
                $tipo_mensaje = 'exito';
            } else {
                $mensaje = "Error al registrar el espacio: " . $mysqli->error;
                $tipo_mensaje = 'error';
            }
            $insertStmt->close();
        }
        $checkStmt->close();
    }
}

// OBTENER ESTADÍSTICAS
$statsQuery = "SELECT 
    COUNT(*) as total,
    SUM(capacidad) as capacidad_total,
    COUNT(CASE WHEN Tipo_salon = 'Aula' THEN 1 END) as aulas,
    COUNT(CASE WHEN Tipo_salon = 'Taller' THEN 1 END) as talleres,
    COUNT(CASE WHEN Tipo_salon = 'Laboratorio' THEN 1 END) as laboratorios,
    COUNT(CASE WHEN Tipo_salon = 'Salon' THEN 1 END) as salones
FROM Espacios";
$statsResult = $mysqli->query($statsQuery);
$stats = $statsResult->fetch_assoc();

$mysqli->close();

include '../front/header.html';
include '../front/form_espacios.php';
?>