<?php
session_start();
include("../PHP/CONEXION/conectar.php");
$error = '';

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
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
  <h2>Inicio de Sesión</h2>
  <?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>
  <form method="POST">
    <label>Usuario:</label><br>
    <input type="text" name="usuario" required><br><br>
    <label>Contraseña:</label><br>
    <input type="password" name="contrasena" required><br><br>
    <button type="submit">Ingresar</button>
  </form>
</body>
</html>
