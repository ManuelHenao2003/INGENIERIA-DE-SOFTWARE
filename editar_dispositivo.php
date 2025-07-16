<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}

include("../CONEXION/conectar.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

// Obtener dispositivo actual
$stmt = $conexion->prepare("SELECT * FROM dispositivos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$dispositivo = $resultado->fetch_assoc();

if (!$dispositivo) {
  echo "Dispositivo no encontrado.";
  exit();
}

// Si se envía el formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $identificacion_equipo = trim($_POST['identificacion_equipo']);
  $tipo = trim($_POST['tipo']);
  $marca = trim($_POST['marca']);
  $descripcion = trim($_POST['descripcion']);
  $estado_id = intval($_POST['estado_id']);

  $stmt = $conexion->prepare("UPDATE dispositivos SET identificacion_equipo = ?, tipo = ?, marca = ?, descripcion = ?, estado_id = ? WHERE id = ?");
  $stmt->bind_param("ssssii", $identificacion_equipo, $tipo, $marca, $descripcion, $estado_id, $id);

  if ($stmt->execute()) {
    $mensaje = "✅ Dispositivo actualizado correctamente.";

    // Registrar en historial
    $accion = "Actualizar dispositivo";
    $descripcion_historial = "Se actualizó el dispositivo $tipo, $marca con ID: $identificacion_equipo";
    $log = $conexion->prepare("INSERT INTO historial (tipo_accion, descripcion, fecha) VALUES (?, ?, NOW())");
    $log->bind_param("ss", $accion, $descripcion_historial);
    $log->execute();
  } else {
    $mensaje = "❌ Error al actualizar el dispositivo.";
  }

  // Recargar los datos actualizados
  $stmt = $conexion->prepare("SELECT * FROM dispositivos WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $resultado = $stmt->get_result();
  $dispositivo = $resultado->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Dispositivo</title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php");?>



<h2>Editar Dispositivo</h2>

<?php if ($mensaje) echo "<p><strong>$mensaje</strong></p>"; ?>

<form method="POST">
  <label>Identificación del equipo:</label>
  <input type="text" name="identificacion_equipo" value="<?php echo htmlspecialchars($dispositivo['identificacion_equipo']); ?>" required>

  <label>Tipo:</label>
  <input type="text" name="tipo" value="<?php echo htmlspecialchars($dispositivo['tipo']); ?>" required>

  <label>Marca:</label>
  <input type="text" name="marca" value="<?php echo htmlspecialchars($dispositivo['marca']); ?>" required>

  <label>Descripción:</label>
  <textarea name="descripcion"><?php echo htmlspecialchars($dispositivo['descripcion']); ?></textarea>

  <label>Estado:</label>
  <select name="estado_id" required>
    <?php
    $estados = $conexion->query("SELECT * FROM estados");
    while ($estado = $estados->fetch_assoc()) {
      $selected = ($estado['id'] == $dispositivo['estado_id']) ? 'selected' : '';
      echo "<option value='{$estado['id']}' $selected>{$estado['nombre']}</option>";
    }
    ?>
  </select>

  <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
