<?php

function conectarDB($server = "localhost", $user = "WinKnow", $pass = "winknow", $db = "db_WinKnow") {
    $conexion = new mysqli($server, $user, $pass, $db);

    if ($conexion->connect_errno) {
        die("Conexión fallida [estoy fuera] : " . $conexion->connect_error);
    };
  
    return $conexion;
}

// variable con valor de la funcion 
$conexion = conectarDB();

?>db_WinKnow
