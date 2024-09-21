<?php
include '../includes/db.php'; // Incluye el archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Manejo de eliminación de vuelo
    if (isset($_POST['delete_flight'])) {
        $id = intval($_POST['id']);
        $sql = "DELETE FROM Flights WHERE id='$id'";
        $message = mysqli_query($conn, $sql) ? "Vuelo eliminado con éxito." : "Error al eliminar vuelo: " . mysqli_error($conn);
    } else {
        // Recopila los datos del vuelo
        $flight_number = mysqli_real_escape_string($conn, $_POST['flight_number']);
        $origin = mysqli_real_escape_string($conn, $_POST['origin']);
        $destination = mysqli_real_escape_string($conn, $_POST['destination']);
        $departure_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
        $arrival_time = mysqli_real_escape_string($conn, $_POST['arrival_time']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);

        if (!empty($_POST['id'])) {
            // Actualiza vuelo existente
            $id = intval($_POST['id']);
            $sql = "UPDATE Flights SET flight_number='$flight_number', origin='$origin', destination='$destination', 
                    departure_time='$departure_time', arrival_time='$arrival_time', price='$price' WHERE id='$id'";
            $message = mysqli_query($conn, $sql) ? "Vuelo actualizado con éxito." : "Error al actualizar vuelo: " . mysqli_error($conn);
        } else {
            // Agrega nuevo vuelo
            $sql = "INSERT INTO Flights (flight_number, origin, destination, departure_time, arrival_time, price) 
                    VALUES ('$flight_number', '$origin', '$destination', '$departure_time', '$arrival_time', '$price')";
            $message = mysqli_query($conn, $sql) ? "Vuelo agregado con éxito." : "Error al agregar vuelo: " . mysqli_error($conn);
        }
    }
}

// Obtiene todos los vuelos
$sql = "SELECT * FROM Flights";
$result = mysqli_query($conn, $sql);
$flights = mysqli_fetch_all($result, MYSQLI_ASSOC);

$edit_flight = null; // Inicializa variable para editar vuelo
if (!empty($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql = "SELECT * FROM Flights WHERE id = '$edit_id'";
    $result = mysqli_query($conn, $sql);
    $edit_flight = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <title>Admin - Administrar Vuelos | Flightmap</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="../users/admin_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        
        <nav>
            <a href="../index.html">Cerrar sesión</a>
            <a href="manage_reservations.php">Administrar Reservaciones</a>
            <a href="manage_flights.php">Administrar Vuelos</a>
        </nav>
    </header>
    
    <main>
        <div class="background-image"></div>
        <h1>Administrar Vuelos</h1>

        <?php if (isset($message)): ?>
            <div class="notification <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <h2><?php echo $edit_flight ? 'Editar Vuelo' : 'Agregar Vuelo'; ?></h2>
        <form action="manage_flights.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $edit_flight['id'] ?? ''; ?>">
            <input type="text" name="flight_number" placeholder="Número de Vuelo" value="<?php echo $edit_flight['flight_number'] ?? ''; ?>" required><br>
            <input type="text" name="origin" placeholder="Origen" value="<?php echo $edit_flight['origin'] ?? ''; ?>" required><br>
            <input type="text" name="destination" placeholder="Destino" value="<?php echo $edit_flight['destination'] ?? ''; ?>" required><br>
            <input type="datetime-local" name="departure_time" value="<?php echo $edit_flight['departure_time'] ?? ''; ?>" required><br>
            <input type="datetime-local" name="arrival_time" value="<?php echo $edit_flight['arrival_time'] ?? ''; ?>" required><br>
            <input type="number" step="0.01" name="price" placeholder="Precio" value="<?php echo $edit_flight['price'] ?? ''; ?>" required><br>
            <button type="submit"><?php echo $edit_flight ? 'Actualizar Vuelo' : 'Agregar Vuelo'; ?></button>
        </form>

        <h2>Vuelos Disponibles</h2>
        <table class="flight-table">
            <thead>
                <tr>
                    <th>Número de Vuelo</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Hora de Salida</th>
                    <th>Hora de Llegada</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights as $flight): ?>
                    <tr>
                        <td><?php echo $flight['flight_number']; ?></td>
                        <td><?php echo $flight['origin']; ?></td>
                        <td><?php echo $flight['destination']; ?></td>
                        <td><?php echo $flight['departure_time']; ?></td>
                        <td><?php echo $flight['arrival_time']; ?></td>
                        <td><?php echo $flight['price']; ?></td>
                        <td>
                            <a href="manage_flights.php?edit_id=<?php echo $flight['id']; ?>" class="btn-editar">Editar</a>
                            <form action="manage_flights.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $flight['id']; ?>">
                                <input type="hidden" name="delete_flight" value="1">
                                <button type="submit" class="btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este vuelo?')">Eliminar</button>
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
