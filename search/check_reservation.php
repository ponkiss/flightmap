<?php
include '../includes/db.php'; // Incluye el archivo de conexión a la base de datos

// Obtiene los parámetros de la consulta, usando valores por defecto
$flight_id = $_GET['flight_id'] ?? null;
$user_id = $_GET['user_id'] ?? null;

// Verifica si se proporciona el ID del vuelo
if ($flight_id) {
    // Prepara la consulta para verificar si hay reservas para el vuelo
    $sql = "SELECT * FROM Reservations WHERE flight_id = ?";
    $stmt = $conn->prepare($sql);

    // Manejo de errores si la preparación de la consulta falla
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la preparación de la consulta.']);
        exit();
    }

    // Vincula el parámetro y ejecuta la consulta
    $stmt->bind_param('i', $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si hay reservas
    $isReserved = $result->num_rows > 0;
    $isUserReservation = false;

    // Verifica si el vuelo está reservado por el usuario actual
    if ($isReserved) {
        $reservation = $result->fetch_assoc();
        $isUserReservation = ($reservation['user_id'] == $user_id);
    }

    // Devuelve el resultado en formato JSON
    echo json_encode([
        'reserved' => $isReserved,
        'is_user_reservation' => $isUserReservation
    ]);
} else {
    // Manejo de caso cuando no se proporciona el ID del vuelo
    echo json_encode(['reserved' => false, 'is_user_reservation' => false]);
}

// Cierra la conexión
$conn->close();
?>
