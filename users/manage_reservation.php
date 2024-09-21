<?php
session_start(); // Iniciar sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    header('Location: login.html'); // Redirigir a la página de inicio de sesión
    exit();
}

// Incluir conexión a la base de datos
include '../includes/db.php';

$user_id = $_SESSION['user']['id']; // Obtener el ID del usuario de la sesión
$notification = ''; // Inicializar notificación
$notification_type = ''; // Inicializar tipo de notificación

// Manejar la eliminación de una reservación
if (isset($_POST['delete_reservation_id'])) {
    $reservation_id = $_POST['delete_reservation_id']; // ID de la reservación a eliminar

    // Preparar y ejecutar la consulta para eliminar la reservación
    $delete_sql = "DELETE FROM Reservations WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('ii', $reservation_id, $user_id);

    if ($stmt->execute()) {
        $notification = 'Reservación eliminada exitosamente.';
        $notification_type = 'success';
    } else {
        $notification = 'Error al eliminar la reservación.';
        $notification_type = 'error';
    }
}

// Obtener las reservaciones del usuario
$sql = "SELECT Reservations.id AS reservation_id, Flights.flight_number, Flights.origin, Flights.destination, 
        Flights.departure_time, Flights.arrival_time, Flights.price
        FROM Reservations
        INNER JOIN Flights ON Reservations.flight_id = Flights.id
        WHERE Reservations.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Obtener todas las reservaciones como un arreglo asociativo
?>

<!DOCTYPE html>
<html lang="es"> <!-- Cambiado a "es" para reflejar el idioma en el que está el contenido -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <title>Mis Reservaciones | Flightmap</title>
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
        <h1>Mis Reservaciones</h1>

        <!-- Notificación de éxito o error -->
        <?php if ($notification): ?>
            <div class="notification <?php echo $notification_type; ?>">
                <?php echo $notification; ?>
            </div>
        <?php endif; ?>

        <table class="flight-table">
            <tr>
                <th>Número de Vuelo</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Salida</th>
                <th>Llegada</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>

            <!-- Mostrar reservaciones o mensaje si no hay -->
            <?php if (count($reservations) > 0): ?>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['flight_number']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['origin']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['destination']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['departure_time']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['arrival_time']); ?></td>
                        <td>$<?php echo htmlspecialchars($reservation['price']); ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="delete_reservation_id" value="<?php echo htmlspecialchars($reservation['reservation_id']); ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No tienes reservaciones.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>

    <footer>
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
