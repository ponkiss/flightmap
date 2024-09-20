<?php
include 'db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $flight_id = $_POST['flight_id'];

    $sql = "INSERT INTO Reservations (user_id, flight_id, payment_status) VALUES ('$user_id', '$flight_id', 'pending')";
    if (mysqli_query($conn, $sql)) {
        echo "Reserva realizada con éxito";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
