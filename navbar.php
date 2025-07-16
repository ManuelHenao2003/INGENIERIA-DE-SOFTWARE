<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<nav class="navbar">
  <div class="navbar-container">
    <ul class="navbar-menu">
      <li><a href="/control_prestamos/DASHBOARD/dashboard.php">🏠 Inicio</a></li>

      <li class="dropdown">
        <a href="#">👥 Usuarios</a>
        <ul class="dropdown-menu">
          <li><a href="/control_prestamos/PHP/USUARIOS/agregar_usuario.php">➕ Agregar Usuario</a></li>
          <li><a href="/control_prestamos/PHP/USUARIOS/listar_usuarios.php">📋 Ver Usuarios</a></li>
        </ul>
      </li>

      <li class="dropdown">
        <a href="#">💻 Equipos</a>
        <ul class="dropdown-menu">
          <li><a href="/control_prestamos/PHP/DISPOSITIVOS/agregar_dispositivo.php">➕ Agregar Equipo</a></li>
          <li><a href="/control_prestamos/PHP/DISPOSITIVOS/listar.php">📋 Ver Equipos</a></li>
          <li><a href="/control_prestamos/PHP/FILTROS_BUSQUEDA/filtrar.php?estado=disponible">✅ Disponibles</a></li>
          <li><a href="/control_prestamos/PHP/FILTROS_BUSQUEDA/filtrar.php?estado=prestado">📦 Prestados</a></li>
          <li><a href="/control_prestamos/PHP/FILTROS_BUSQUEDA/filtrar.php?estado=dañado">⚠️ Dañados</a></li>
        </ul>
      </li>

      <li><a href="/control_prestamos/PHP/PRESTAMOS/registrar_prestamo.php">📑 Registrar Préstamo</a></li>
      <li><a href="/control_prestamos/PHP/FILTROS_BUSQUEDA/buscar.php?q=">🔍 Buscar</a></li>
      <li><a href="/control_prestamos/PHP/HISTORIAL/historial.php">🕓 Historial</a></li>
      <li><a href="/control_prestamos/AUTH/logout.php">🚪 Cerrar sesión</a></li>
    </ul>

    <div class="navbar-user">
      👤 <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Invitado'; ?>
    </div>
  </div>
</nav>