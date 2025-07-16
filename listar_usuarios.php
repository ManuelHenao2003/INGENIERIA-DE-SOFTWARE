<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: /control_prestamos/PHP/AUTH/login.php");
  exit();
}

include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/CONEXION/conectar.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Usuarios</title>
  <link rel="stylesheet" href="/control_prestamos/CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php"); ?>

<h2>Lista de Usuarios</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Cédula</th>
      <th>Correo</th>
      <th>Usuario</th>
      <th>Rol</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $consulta = "SELECT u.id, u.nombre_completo, u.cedula, u.correo, u.usuario, r.nombre AS rol
                 FROM usuarios u
                 JOIN roles r ON u.rol_id = r.id";
    $resultado = $conexion->query($consulta);

    while ($fila = $resultado->fetch_assoc()) {
      echo "<tr>";
      echo "<td>{$fila['id']}</td>";
      echo "<td>{$fila['nombre_completo']}</td>";
      echo "<td>{$fila['cedula']}</td>";
      echo "<td>{$fila['correo']}</td>";
      echo "<td>{$fila['usuario']}</td>";
      echo "<td>{$fila['rol']}</td>";
      echo "<td>
              <a href='editar_usuario.php?id={$fila['id']}'>Editar</a> | 
              <a href='eliminar_usuario.php?id={$fila['id']}' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>
            </td>";
      echo "</tr>";
    }
    ?>
  </tbody>
</table>

</body>
</html>