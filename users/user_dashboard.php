<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado y tiene el rol adecuado
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    // Redirigir al login si no está autenticado
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
    
    <title>Flightmap</title>
</head>
<body>
    <!-- Encabezado con logo y navegación -->
    <header>
        <div class="logo">
            <a href="user_dashboard.php">
                <!-- Logo del sitio enlazado al dashboard del usuario -->
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        

        <!-- Menú de navegación principal -->
        <nav>
            <a href="../index.html">Cerrar Sesión</a>
            <a href="user_search.php">Agendar una Reservación</a>
            <a href="user_search.php">Buscar Vuelos</a>
        </nav>
    </header>

    <!-- Contenido principal de la página -->
    <main>
        <!-- Imagen de fondo decorativa -->
        <div class="background-image"></div>

        <!-- Sección hero que saluda al usuario autenticado -->
        <section class="hero">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1>
            <p>Encuentra y reserva los mejores vuelos al mejor precio.</p>

            <!-- Botones de acción (CTA) -->
            <div class="cta-buttons">
                <a href="user_search.php" class="btn">Buscar Vuelos</a>
                <a href="user_search.php" class="btn">Agendar una Reservación</a>
            </div>
        </section>
    </main>

    <!-- Pie de página con derechos reservados -->
    <footer>
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
