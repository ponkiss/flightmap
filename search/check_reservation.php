<?php
include '../includes/db.php'; // Conexión a la base de datos

$flight_id = $_GET['flight_id'] ?? null;

if ($flight_id) {
    // Verificar si el vuelo ya ha sido reservado
    $sql = "SELECT * FROM Reservations WHERE flight_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $isReserved = $result->num_rows > 0;

    echo json_encode(['reserved' => $isReserved]);
} else {
    echo json_encode(['reserved' => false]);
}
?>
