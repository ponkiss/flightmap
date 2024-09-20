<?php
include '../includes/db.php'; // Conexión a la base de datos

// Obtener todos los vuelos de la base de datos
$sql = "SELECT * FROM Flights";
$result = mysqli_query($conn, $sql);
$flights = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Devolver los vuelos como JSON
echo json_encode($flights);
?>
