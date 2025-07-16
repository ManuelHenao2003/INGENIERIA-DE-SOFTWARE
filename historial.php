<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}

include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/CONEXION/conectar.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial del Sistema</title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php");?>

<h2>Historial de Actividades</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Fecha</th>
      <th>Acción</th>
      <th>Descripción</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $consulta = "SELECT * FROM historial ORDER BY fecha DESC";
    $resultado = $conexion->query($consulta);

    while ($fila = $resultado->fetch_assoc()) {
      echo "<tr>";
      echo "<td>{$fila['id']}</td>";
      echo "<td>{$fila['fecha']}</td>";
      echo "<td>{$fila['tipo_accion']}</td>";
      echo "<td>{$fila['descripcion']}</td>";
      echo "</tr>";
    }
    ?>
  </tbody>
</table>

</body>
</html>
