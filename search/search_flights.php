<?php
// Incluye el archivo de conexión a la base de datos
include '../includes/db.php';

// Consulta para obtener todos los vuelos
$sql = "SELECT * FROM Flights";
$result = mysqli_query($conn, $sql);

// Verifica si la consulta fue exitosa
if (!$result) {
    // Devuelve un error en caso de fallo en la consulta
    http_response_code(500); // Establece el código de respuesta HTTP a 500 (Error interno del servidor)
    echo json_encode(['error' => 'Error en la consulta de vuelos.']); // Mensaje de error en formato JSON
    exit(); // Termina la ejecución del script
}

// Obtiene todos los vuelos como un arreglo asociativo
$flights = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Retorna los vuelos en formato JSON
echo json_encode($flights);

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>
