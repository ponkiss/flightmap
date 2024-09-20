<?php
include 'db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_number = $_POST['flight_number'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    $sql = "INSERT INTO Flights (flight_number, origin, destination, departure_time, arrival_time, price) 
            VALUES ('$flight_number', '$origin', '$destination', '$departure_time', '$arrival_time', '$price')";

    if (mysqli_query($conn, $sql)) {
        echo "Vuelo agregado con éxito";
        header('Location: admin_dashboard.php'); // Redirigir de nuevo al panel de administración
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
