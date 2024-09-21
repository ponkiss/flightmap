<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de 'user'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es"> <!-- Cambié el idioma a "es" -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    
    <title>Flightmap</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        

        <nav>
            <a href="../index.html">Cerrar sesión</a>
            <a href="user_search.php">Agendar una reservación</a>
            <a href="manage_reservation.php">Mis reservaciones</a>
            <a href="user_search.php">Buscar vuelos</a>
        </nav>
    </header>

    <main>
        <div class="background-image"></div>

        <!-- Sección de bienvenida -->
        <section class="hero">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1> <!-- Escapar caracteres especiales por seguridad -->
            <p>Encuentra y reserva los mejores vuelos al mejor precio.</p>

            <div class="cta-buttons">
                <a href="user_search.php" class="btn">Buscar Vuelos</a>
                <a href="manage_reservation.php" class="btn">Mis Reservaciones</a>
                <a href="user_search.php" class="btn">Agendar una reservación</a>
            </div>
        </section>
    </main>

    <footer>
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
