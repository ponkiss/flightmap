<?php
include 'db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'view') {
    $user_id = $_POST['user_id'];

    $sql = "SELECT * FROM Reservations JOIN Flights ON Reservations.flight_id = Flights.id WHERE Reservations.user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $reservations = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($reservations);
}

// Función para actualizar el estado de pago
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'pay') {
    $reservation_id = $_POST['reservation_id'];

    $sql = "UPDATE Reservations SET payment_status = 'paid' WHERE id = '$reservation_id'";
    if (mysqli_query($conn, $sql)) {
        echo "Pago realizado con éxito";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
