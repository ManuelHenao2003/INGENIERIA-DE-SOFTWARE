<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}
include("../CONEXION/conectar.php");

$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Buscar</title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php"); ?>

<h2>Buscar Dispositivos o Usuarios</h2>

<form method="get">
  <input type="text" name="q" placeholder="Buscar por ID, cédula o identificación del equipo" value="<?php echo htmlspecialchars($busqueda); ?>">
  <button type="submit">Buscar</button>
</form>

<?php
if ($busqueda !== '') {
  echo "<h3>Resultados:</h3>";
  echo "<table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Tipo</th>
              <th>Identificación</th>
              <th>Rol / Estado</th>
            </tr>
          </thead>
          <tbody>";

  $param = "%$busqueda%";

  // Buscar en usuarios
  $stmt = $conexion->prepare("SELECT u.id, u.nombre_completo, u.cedula, u.usuario, r.nombre AS rol 
                              FROM usuarios u
                              JOIN roles r ON u.rol_id = r.id
                              WHERE u.cedula LIKE ? OR u.id LIKE ?");
  $stmt->bind_param("ss", $param, $param);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nombre_completo']}</td>
            <td>Usuario</td>
            <td>{$row['cedula']}</td>
            <td>{$row['rol']}</td>
          </tr>";
  }

  // Buscar en dispositivos
  $stmt2 = $conexion->prepare("SELECT d.id, d.identificacion_equipo, d.tipo, d.marca, e.nombre AS estado 
                               FROM dispositivos d
                               JOIN estados e ON d.estado_id = e.id
                               WHERE d.identificacion_equipo LIKE ? OR d.id LIKE ?");
  $stmt2->bind_param("ss", $param, $param);
  $stmt2->execute();
  $res2 = $stmt2->get_result();
  while ($row = $res2->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['tipo']} - {$row['marca']}</td>
            <td>Dispositivo</td>
            <td>{$row['identificacion_equipo']}</td>
            <td>{$row['estado']}</td>
          </tr>";
  }

  echo "</tbody></table>";
}
?>

</body>
</html>
