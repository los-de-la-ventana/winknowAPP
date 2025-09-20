
<?php
//--------------------------------------------
// FUNCION PARA CONECTAR A LA BASE DE DATOS SERVIDOR BRUNO
//--------------------------------------------
function conectarDBSERVER($server = "localhost", $user = "WinKnow", $pass = "winknow", $db = "db_WinKnow") {
    $conexion = new mysqli($server, $user, $pass, $db);

    if ($conexion->connect_errno) {
        die("Conexión fallida [estoy fuera] : " . $conexion->connect_error);
    };
  
    return $conexion;
}

// variable con valor de la funcion 
$conexion = conectarDBSERVER();
?>
