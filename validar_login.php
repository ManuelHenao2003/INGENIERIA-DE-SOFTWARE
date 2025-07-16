<?php
session_start();
include("../PHP/CONEXION/conectar.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conexion->prepare("SELECT u.id, u.usuario, u.contrasena, r.nombre AS rol 
                                FROM usuarios u 
                                JOIN roles r ON u.rol_id = r.id 
                                WHERE u.usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();
        if ($contrasena == $fila['contrasena']) {
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'];
            header("Location: ../DASHBOARD/dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=1");
        }
    } else {
        header("Location: login.php?error=1");
    }
}
?>
