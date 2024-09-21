<?php
include '../includes/db.php'; // Incluye el archivo de conexión a la base de datos

// Verifica si se ha enviado un formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si se ha solicitado eliminar una reservación
    if (isset($_POST['delete_reservation'])) {
        $id = intval($_POST['id']); // Convierte el ID a entero
        // Consulta para eliminar la reservación
        $sql = "DELETE FROM Reservations WHERE id='$id'";
        // Mensaje de éxito o error al eliminar la reservación
        $message = mysqli_query($conn, $sql) ? "Reservación eliminada con éxito" : "Error al eliminar reservación: " . mysqli_error($conn);
    }
}

// Consulta para obtener las reservaciones actuales
$sql = "SELECT Reservations.id AS reservation_id, Flights.flight_number, Flights.origin, Flights.destination, 
        Flights.departure_time, Flights.arrival_time, Users.username, Users.email 
        FROM Reservations 
        JOIN Flights ON Reservations.flight_id = Flights.id 
        JOIN Users ON Reservations.user_id = Users.id";
$result = mysqli_query($conn, $sql);
$reservations = mysqli_fetch_all($result, MYSQLI_ASSOC); // Obtiene todas las reservaciones
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <title>Admin - Administrar Reservaciones | Flightmap</title>
</head>
<body>
    <header>
        <!-- Logo con enlace al panel de administración -->
        <div class="logo">
            <a href="../users/admin_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        
        <nav>
            <a href="../index.html">Cerrar Sesión</a>
            <a href="manage_reservations.php">Administrar Reservaciones</a>
            <a href="manage_flights.php">Administrar Vuelos</a>
        </nav>
    </header>

    <main>
        <div class="background-image"></div>
        <h1>Administrar Reservaciones</h1>

        <?php if (isset($message)): ?>
        <div class="notification <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?> <!-- Muestra el mensaje de éxito o error -->
        </div>
        <?php endif; ?>

        <h2>Reservaciones Actuales</h2>
        <table class="flight-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Número de Vuelo</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Hora de Salida</th>
                    <th>Hora de Llegada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['username']); ?></td> <!-- Sanitización de entrada -->
                    <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['flight_number']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['origin']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['destination']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['departure_time']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['arrival_time']); ?></td>
                    <td>
                        <!-- Formulario para eliminar la reservación -->
                        <form action="manage_reservations.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $reservation['reservation_id']; ?>">
                            <input type="hidden" name="delete_reservation" value="1">
                            <button type="submit" class="btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar esta reservación?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
