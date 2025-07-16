<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}

include("../CONEXION/conectar.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = $_POST['nombre_completo'];
  $cedula = $_POST['cedula'];
  $correo = $_POST['correo'];
  $usuario = $_POST['usuario'];
  $contrasena = $_POST['contrasena'];
  $rol_id = $_POST['rol_id'];

  $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_completo, cedula, correo, usuario, contrasena, rol_id) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssi", $nombre, $cedula, $correo, $usuario, $contrasena, $rol_id);

  if ($stmt->execute()) {
    $mensaje = "✅ Usuario agregado correctamente.";

    // Registro en historial
    $accion = "Agregar usuario";
    $descripcion = "Se agregó el usuario $nombre";
    $log = $conexion->prepare("INSERT INTO historial (tipo_accion, descripcion, fecha) VALUES (?, ?, NOW())");
    $log->bind_param("ss", $accion, $descripcion);
    $log->execute();
  } else {
    $mensaje = "❌ Error al agregar el usuario.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Usuario</title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
</head>
<body>

<?php include("../NAVBAR/navbar.php"); ?>

<h2>Agregar Usuario</h2>

<?php if ($mensaje): ?>
  <p><strong><?php echo $mensaje; ?></strong></p>
<?php endif; ?>

<form method="POST">
  <label>Nombre completo:</label>
  <input type="text" name="nombre_completo" required>

  <label>Cédula:</label>
  <input type="text" name="cedula" required>

  <label>Correo:</label>
  <input type="email" name="correo" required>

  <label>Usuario:</label>
  <input type="text" name="usuario" required>

  <label>Contraseña:</label>
  <input type="password" name="contrasena" required>

  <label>Rol:</label>
  <select name="rol_id" required>
    <option value="1">Administrativo</option>
    <option value="2">Maestro</option>
    <option value="3">Aprendiz</option>
  </select>

  <button type="submit">Registrar</button>
</form>

</body>
</html>