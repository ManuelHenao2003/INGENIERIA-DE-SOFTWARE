<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: /control_prestamos/PHP/AUTH/login.php");
  exit();
}
include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php");


include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/CONEXION/conectar.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $identificacion = trim($_POST['identificacion_equipo']);
  $tipo = trim($_POST['tipo']);
  $marca = trim($_POST['marca']);
  $descripcion = trim($_POST['descripcion']);
  $estado_id = 1; // Disponible

  // Verificar duplicado
  $verificar = $conexion->prepare("SELECT id FROM dispositivos WHERE identificacion_equipo = ?");
  $verificar->bind_param("s", $identificacion);
  $verificar->execute();
  $resultado = $verificar->get_result();

  if ($resultado->num_rows > 0) {
    $mensaje = "⚠️ Ya existe un dispositivo con esa identificación.";
  } else {
    $stmt = $conexion->prepare("INSERT INTO dispositivos (identificacion_equipo, tipo, marca, descripcion, estado_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $identificacion, $tipo, $marca, $descripcion, $estado_id);

    if ($stmt->execute()) {
      $mensaje = "✅ Dispositivo agregado correctamente.";

      $tipo_accion = "Agregar dispositivo";
      $descripcion_historial = "Se agregó el dispositivo $tipo, $marca con el ID: $identificacion";

      $log = $conexion->prepare("INSERT INTO historial (tipo_accion, descripcion, fecha) VALUES (?, ?, NOW())");
      $log->bind_param("ss", $tipo_accion, $descripcion_historial);
      $log->execute();
    } else {
      $mensaje = "❌ Error al registrar el dispositivo.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Dispositivo</title>
  <link rel="stylesheet" href="/control_prestamos/CSS/estilos.css">
</head>
<body>

<h2>Agregar Nuevo Dispositivo</h2>

<?php if ($mensaje) echo "<p><strong>$mensaje</strong></p>"; ?>

<form method="POST">
  <label>Identificación del equipo:</label>
  <input type="text" name="identificacion_equipo" required>

  <label>Tipo:</label>
  <input type="text" name="tipo" required>

  <label>Marca:</label>
  <input type="text" name="marca" required>

  <label>Descripción:</label>
  <textarea name="descripcion"></textarea>

  <button type="submit">Agregar</button>
</form>

</body>
</html>