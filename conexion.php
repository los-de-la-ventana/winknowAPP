<?php
//--------------------------------------------
// FUNCION PARA CONECTAR A LA BASE DE DATOS EN LOCALHOST
// CAMBIAR VARIABLES SEGUN SEA NECESARIO PARA EL SERVIDOR DEL ITS
//--------------------------------------------
function conectarDB($server = "localhost", $user = "root", $pass = "", $db = "db_WinKnow") {
    $conexion = new mysqli($server, $user, $pass, $db);

    if ($conexion->connect_errno) {
        die("Conexión fallida [estoy fuera] : " . $conexion->connect_error);
    };
    
    return $conexion;
}

// variable con valor de la funcion 
$conexion = conectarDB();

?>
