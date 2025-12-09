<?php
//$servername = 'b88e0bd2df17.sn.mynetname.net:3306';
  $servername = 'localhost:3307';
   //$database = "bbsnetwo_Datos-clientes";
    $database = 'suscripciones';
    $username = 'root';
    $password = 'root';

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Soporte para caracteres especiales (ñ, tildes, etc.)
$conexion->set_charset("utf8mb4");
?>
