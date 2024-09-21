<?php
session_start();

// Verifica si el usuario está autenticado y si es un administrador
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.html'); // Redirige a la página de inicio de sesión si no está autenticado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es"> <!-- Cambiado el idioma a "es" -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Enlace a la hoja de estilos CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    
    <!-- Favicon del sitio web -->
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    
    <!-- Título de la página -->
    <title>Admin | Flightmap</title>
</head>
<body>
    <header>
        <!-- Logo del sitio web con enlace al panel de administración -->
        <div class="logo">
            <a href="admin_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        

        <!-- Barra de navegación -->
        <nav>
            <a href="../index.html">Cerrar sesión</a> <!-- Cambiado a "Cerrar sesión" para mantener consistencia -->
            <a href="../flights/manage_reservations.php">Administrar Reservaciones</a>
            <a href="../flights/manage_flights.php">Administrar Vuelos</a>
        </nav>
    </header>

    <main>
        <!-- Imagen de fondo -->
        <div class="background-image"></div>

        <!-- Sección principal con el mensaje de bienvenida -->
        <section class="hero">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1> <!-- Sanitización de salida para prevenir XSS -->
            <p>¿Qué deseas hacer hoy?</p>

            <!-- Botones de llamada a la acción (CTA) -->
            <div class="cta-buttons">
                <a href="../flights/manage_flights.php" class="btn">Administrar Vuelos</a>
                <a href="../flights/manage_reservations.php" class="btn">Administrar Reservaciones</a>
            </div>
        </section>
    </main>

    <footer>
        <!-- Pie de página -->
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
