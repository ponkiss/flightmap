<?php
include 'db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];

    $sql = "SELECT * FROM Flights WHERE origin = '$origin' AND destination = '$destination' AND departure_time >= '$departure_time'";
    $result = mysqli_query($conn, $sql);
    $flights = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($flights);
}
?>
