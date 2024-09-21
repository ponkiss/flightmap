<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    // Redirigir al login si no es administrador
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metaetiquetas esenciales para la codificación de caracteres y diseño responsivo -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Vinculación a los estilos y favicon -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    
    <title>Admin | Flightmap</title>
</head>
<body>
    <!-- Encabezado con logo y navegación -->
    <header>
        <div class="logo">
            <a href="admin_dashboard.php">
                <!-- Logo del sitio enlazado al dashboard del administrador -->
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        

        <!-- Menú de navegación principal -->
        <nav>
            <a href="../index.html">Cerrar Sesión</a>
            <a href="../flights/manage_reservations.php">Administrar Reservaciones</a>
            <a href="../flights/manage_flights.php">Administrar Vuelos</a>
        </nav>
    </header>

    <!-- Contenido principal de la página -->
    <main>
        <!-- Imagen de fondo decorativa -->
        <div class="background-image"></div>

        <!-- Sección hero que saluda al usuario autenticado -->
        <section class="hero">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1>
            <p>¿Qué deseas hacer hoy?</p>

            <!-- Botones de acción (CTA) -->
            <div class="cta-buttons">
                <a href="../flights/manage_flights.php" class="btn">Administrar Vuelos</a>
                <a href="../flights/manage_reservations.php" class="btn">Administrar Reservaciones</a>
            </div>
        </section>
    </main>

    <!-- Pie de página con derechos reservados -->
    <footer>
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
