<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}
include("../CONEXION/conectar.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre_completo = $_POST['nombre_completo'];
  $cedula = $_POST['cedula'];
  $correo = $_POST['correo'];
  $usuario = $_POST['usuario'];
  $contrasena = $_POST['contrasena'];
  $rol_id = $_POST['rol_id'];

  $stmt = $conexion->prepare("UPDATE usuarios SET nombre_completo=?, cedula=?, correo=?, usuario=?, contrasena=?, rol_id=? WHERE id=?");
  $stmt->bind_param("ssssssi", $nombre_completo, $cedula, $correo, $usuario, $contrasena, $rol_id, $id);
  if ($stmt->execute()) {
    $mensaje = "✅ Usuario actualizado correctamente.";
  } else {
    $mensaje = "❌ Error al actualizar el usuario.";
  }
}

// Obtener datos actuales
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario_actual = $resultado->fetch_assoc();

if (!$usuario_actual) {
  echo "Usuario no encontrado.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
</head>
<body>

<?php include("../navbar.php"); ?>

<h2>Editar Usuario</h2>

<?php if ($mensaje) echo "<p><strong>$mensaje</strong></p>"; ?>

<form method="POST">
  <label>Nombre Completo:</label>
  <input type="text" name="nombre_completo" value="<?php echo htmlspecialchars($usuario_actual['nombre_completo']); ?>" required>

  <label>Cédula:</label>
  <input type="text" name="cedula" value="<?php echo htmlspecialchars($usuario_actual['cedula']); ?>" required>

  <label>Correo:</label>
  <input type="email" name="correo" value="<?php echo htmlspecialchars($usuario_actual['correo']); ?>" required>

  <label>Usuario:</label>
  <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario_actual['usuario']); ?>" required>

  <label>Contraseña:</label>
  <input type="password" name="contrasena" value="<?php echo htmlspecialchars($usuario_actual['contrasena']); ?>" required>

  <label>Rol:</label>
  <select name="rol_id" required>
    <option value="">-- Seleccionar Rol --</option>
    <?php
    $roles = $conexion->query("SELECT id, nombre FROM roles");
    while ($rol = $roles->fetch_assoc()) {
      $selected = ($rol['id'] == $usuario_actual['rol_id']) ? "selected" : "";
      echo "<option value='{$rol['id']}' $selected>{$rol['nombre']}</option>";
    }
    ?>
  </select>

  <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
