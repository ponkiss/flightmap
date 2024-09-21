<?php
// Definición de las credenciales de la base de datos
$servername = "localhost"; // Nombre del servidor
$username = "root"; // Nombre de usuario
$password = ""; // Contraseña del usuario
$dbname = "flight_reservations"; // Nombre de la base de datos

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    // Mensaje de error en caso de fallo en la conexión
    die("Conexión fallida: " . mysqli_connect_error());
}

// Si se necesita, se puede agregar código aquí para realizar consultas a la base de datos

?>
