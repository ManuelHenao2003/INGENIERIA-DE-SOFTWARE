<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrativo') {
  header("Location: ../../AUTH/login.php");
  exit();
}
include("../CONEXION/conectar.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
  $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: listar_usuarios.php");
exit();
?>
