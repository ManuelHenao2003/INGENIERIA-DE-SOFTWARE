<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../LOGIN/login.php");
  exit();
}
include("../CONEXION/conectar.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Dispositivos</title>
  <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>

<?php include("../NAVBAR/navbar.php"); ?>

<h2>Lista de Dispositivos</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Identificación del Equipo</th>
      <th>Tipo</th>
      <th>Marca</th>
      <th>Descripción</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $consulta = "SELECT d.id, d.identificacion_equipo, d.tipo, d.marca, d.descripcion, e.nombre AS estado_nombre
                 FROM dispositivos d
                 JOIN estados e ON d.estado_id = e.id";
    $resultado = $conexion->query($consulta);

    while ($fila = $resultado->fetch_assoc()) {
      echo "<tr>";
      echo "<td>{$fila['id']}</td>";
      echo "<td>{$fila['identificacion_equipo']}</td>";
      echo "<td>{$fila['tipo']}</td>";
      echo "<td>{$fila['marca']}</td>";
      echo "<td>{$fila['descripcion']}</td>";
      echo "<td>{$fila['estado_nombre']}</td>";
      echo "<td>
              <a href='editar_dispositivo.php?id={$fila['id']}'>Editar</a> | 
              <a href='eliminar_dispositivo.php?id={$fila['id']}' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>
            </td>";
      echo "</tr>";
    }
    ?>
  </tbody>
</table>

</body>
</html>
