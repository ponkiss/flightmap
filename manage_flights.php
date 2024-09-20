<?php
include 'db.php'; // Conexión a la base de datos

$sql = "SELECT * FROM Flights";
$result = mysqli_query($conn, $sql);
$flights = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Vuelos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Gestionar Vuelos</h1>
    <table border="1">
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
                    <a href="edit_flight.php?id=<?php echo $flight['id']; ?>">Editar</a> |
                    <a href="delete_flight.php?id=<?php echo $flight['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este vuelo?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
