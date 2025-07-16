<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: /control_prestamos/PHP/AUTH/login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Control</title>
  <link rel="stylesheet" href="/control_prestamos/CSS/estilos.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/control_prestamos/PHP/NAVBAR/navbar.php"); ?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']; ?></h2>

<p>Desde aquí puedes administrar equipos, préstamos, usuarios y consultar el historial completo del sistema.</p>

</body>
</html>