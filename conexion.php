<?php
//--------------------------------------------
// FUNCION PARA CONECTAR A LA BASE DE DATOS EN LOCALHOST
//--------------------------------------------
function conectarDB($server = "localhost", $user = "root", $pass = "", $db = "winknow") {
    $conexion = new mysqli($server, $user, $pass, $db);

    if ($conexion->connect_errno) {
        die("Conexión fallida [estoy fuera] : " . $conexion->connect_error);
    };
  
    return $conexion;
}

// variable con valor de la funcion 
$conexion = conectarDB();

?>


<?php
// para el coso de bruno cambiar
// user = WinKnow
// pass = winknow
// db = db_WinKnow
?>