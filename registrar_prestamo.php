<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: /control_prestamos/PHP/AUTH/login.php");
  exit();
}

include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/CONEXION/conectar.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $usuario_id = $_POST['usuario_id'];
  $dispositivo_id = $_POST['dispositivo_id'];
  $fecha_prestamo = date('Y-m-d H:i:s');

  $query_usuario = $conexion->prepare("SELECT nombre_completo FROM usuarios WHERE id = ?");
  $query_usuario->bind_param("i", $usuario_id);
  $query_usuario->execute();
  $result_usuario = $query_usuario->get_result();
  $usuario = $result_usuario->fetch_assoc();

  $query_dispositivo = $conexion->prepare("SELECT identificacion_equipo, estado_id FROM dispositivos WHERE id = ?");
  $query_dispositivo->bind_param("i", $dispositivo_id);
  $query_dispositivo->execute();
  $result_dispositivo = $query_dispositivo->get_result();
  $dispositivo = $result_dispositivo->fetch_assoc();

  if ($dispositivo && $dispositivo['estado_id'] != 1) {
    $mensaje = "⚠️ El equipo no está disponible para préstamo.";
  } else {
    $insertar = $conexion->prepare("INSERT INTO prestamos (usuario_id, dispositivo_id, fecha_prestamo) VALUES (?, ?, ?)");
    $insertar->bind_param("iis", $usuario_id, $dispositivo_id, $fecha_prestamo);
    $insertar->execute();

    $actualizar = $conexion->prepare("UPDATE dispositivos SET estado_id = 2 WHERE id = ?");
    $actualizar->bind_param("i", $dispositivo_id);
    $actualizar->execute();

    $mensaje = "✅ Préstamo registrado correctamente.";

    $tipo_accion = "Préstamo";
    $descripcion = "Usuario {$usuario['nombre_completo']} solicitó el préstamo del equipo {$dispositivo['identificacion_equipo']}";
    $log = $conexion->prepare("INSERT INTO historial (tipo_accion, descripcion, fecha) VALUES (?, ?, NOW())");
    $log->bind_param("ss", $tipo_accion, $descripcion);
    $log->execute();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Préstamo</title>
  <link rel="stylesheet" href="/control_prestamos/CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php"); ?>

<h2>Registrar Préstamo</h2>

<?php if ($mensaje) echo "<p><strong>$mensaje</strong></p>"; ?>

<form method="POST">
  <label>Seleccionar Usuario:</label>
  <select name="usuario_id" required>
    <option value="">-- Seleccionar --</option>
    <?php
    $usuarios = $conexion->query("SELECT id, nombre_completo FROM usuarios WHERE rol_id != 1");
    while ($usuario = $usuarios->fetch_assoc()) {
      echo "<option value='{$usuario['id']}'>{$usuario['nombre_completo']}</option>";
    }
    ?>
  </select>

  <label>Seleccionar Dispositivo:</label>
  <select name="dispositivo_id" required>
    <option value="">-- Seleccionar --</option>
    <?php
    $dispositivos = $conexion->query("SELECT id, identificacion_equipo FROM dispositivos WHERE estado_id = 1");
    while ($equipo = $dispositivos->fetch_assoc()) {
      echo "<option value='{$equipo['id']}'>{$equipo['identificacion_equipo']}</option>";
    }
    ?>
  </select>

  <button type="submit">Registrar</button>
</form>

</body>
</html>