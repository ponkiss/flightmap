<?php
include '../includes/db.php'; // Conexión a la base de datos

// Manejar la solicitud POST para eliminar una reservación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_reservation'])) {
        $id = intval($_POST['id']);
        $sql = "DELETE FROM Reservations WHERE id='$id'";
        $message = mysqli_query($conn, $sql) ? "Reservación eliminada con éxito" : "Error al eliminar reservación: " . mysqli_error($conn);
    }
}

// Obtener todas las reservaciones con detalles del vuelo y del usuario
$sql = "SELECT Reservations.id as reservation_id, Flights.flight_number, Flights.origin, Flights.destination, Flights.departure_time, Flights.arrival_time, 
        Users.username, Users.email 
        FROM Reservations 
        JOIN Flights ON Reservations.flight_id = Flights.id 
        JOIN Users ON Reservations.user_id = Users.id";
$result = mysqli_query($conn, $sql);
$reservations = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Tabla de reservaciones -->
        <h2>Reservaciones Actuales</h2>
        <table class="reservation-table">
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
                    <td><?php echo $reservation['username']; ?></td>
                    <td><?php echo $reservation['email']; ?></td>
                    <td><?php echo $reservation['flight_number']; ?></td>
                    <td><?php echo $reservation['origin']; ?></td>
                    <td><?php echo $reservation['destination']; ?></td>
                    <td><?php echo $reservation['departure_time']; ?></td>
                    <td><?php echo $reservation['arrival_time']; ?></td>
                    <td>
                        <form action="manage_reservations.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $reservation['reservation_id']; ?>">
                            <input type="hidden" name="delete_reservation" value="1">
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta reservación?')">Eliminar</button>
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
