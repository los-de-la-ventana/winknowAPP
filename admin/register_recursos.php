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

// PROCESAR REGISTRO DE NUEVO RECURSO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    
    if ($_POST['accion'] === 'registrar') {
        $nombre_recurso = trim($_POST['nombre_recurso']);
        $id_espacio = intval($_POST['id_espacio']);
        
        // Validar campos obligatorios
        if (empty($nombre_recurso) || $id_espacio <= 0) {
            $mensaje = "Todos los campos son obligatorios";
            $tipo_mensaje = 'error';
        } else {
            // Verificar que el espacio existe
            $checkEspacio = "SELECT IdEspacio FROM espacios WHERE IdEspacio = ?";
            $stmtCheck = $mysqli->prepare($checkEspacio);
            $stmtCheck->bind_param("i", $id_espacio);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            
            if ($stmtCheck->num_rows === 0) {
                $mensaje = "El espacio seleccionado no existe";
                $tipo_mensaje = 'error';
            } else {
                // Insertar nuevo recurso
                $insertQuery = "INSERT INTO recursos (nombre_Recurso, IdEspacio) VALUES (?, ?)";
                $insertStmt = $mysqli->prepare($insertQuery);
                $insertStmt->bind_param("si", $nombre_recurso, $id_espacio);
                
                if ($insertStmt->execute()) {
                    $mensaje = "Recurso '$nombre_recurso' registrado exitosamente";
                    $tipo_mensaje = 'exito';
                } else {
                    $mensaje = "Error al registrar el recurso: " . $mysqli->error;
                    $tipo_mensaje = 'error';
                }
                $insertStmt->close();
            }
            $stmtCheck->close();
        }
    }
}

// OBTENER LISTA DE ESPACIOS DISPONIBLES
$queryEspacios = "SELECT IdEspacio, NumSalon, Tipo_salon, capacidad FROM espacios ORDER BY NumSalon";
$resultEspacios = $mysqli->query($queryEspacios);

$mysqli->close();

include '../front/header.html';
include '../front/register_recursos_form.php';
?>