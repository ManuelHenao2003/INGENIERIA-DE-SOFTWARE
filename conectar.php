<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "control_dispositivos";

$conexion = new mysqli($host, $usuario, $contrasena, $bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
