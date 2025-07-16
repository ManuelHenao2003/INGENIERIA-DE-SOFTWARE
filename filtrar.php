<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}
include("../CONEXION/conectar.php");

$estado = isset($_GET['estado']) ? strtolower(trim($_GET['estado'])) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Filtrar Equipos</title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php"); ?>


<h2>Equipos con estado: <?php echo ucfirst(htmlspecialchars($estado)); ?></h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Identificación</th>
      <th>Tipo</th>
      <th>Marca</th>
      <th>Descripción</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (!empty($estado)) {
      $stmt = $conexion->prepare("
        SELECT d.id, d.identificacion_equipo, d.tipo, d.marca, d.descripcion, e.nombre AS estado_nombre
        FROM dispositivos d
        JOIN estados e ON d.estado_id = e.id
        WHERE e.nombre = ?
      ");
      $stmt->bind_param("s", $estado);
      $stmt->execute();
      $resultado = $stmt->get_result();

      while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$fila['id']}</td>";
        echo "<td>{$fila['identificacion_equipo']}</td>";
        echo "<td>{$fila['tipo']}</td>";
        echo "<td>{$fila['marca']}</td>";
        echo "<td>{$fila['descripcion']}</td>";
        echo "<td>{$fila['estado_nombre']}</td>";
        echo "<td>
                <a href='../DISPOSITIVOS/editar_dispositivo.php?id={$fila['id']}'>Editar</a> | 
                <a href='../DISPOSITIVOS/eliminar_dispositivo.php?id={$fila['id']}' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>
              </td>";
        echo "</tr>";
      }
    }
    ?>
  </tbody>
</table>

</body>
</html>
