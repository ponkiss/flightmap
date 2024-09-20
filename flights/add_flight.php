<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Vuelo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Agregar Vuelo</h1>
    <form action="process_flight.php" method="POST">
        <input type="text" name="flight_number" placeholder="Número de Vuelo" required><br>
        <input type="text" name="origin" placeholder="Origen" required><br>
        <input type="text" name="destination" placeholder="Destino" required><br>
        <input type="datetime-local" name="departure_time" placeholder="Hora de Salida" required><br>
        <input type="datetime-local" name="arrival_time" placeholder="Hora de Llegada" required><br>
        <input type="number" step="0.01" name="price" placeholder="Precio" required><br>
        <button type="submit">Agregar Vuelo</button>
    </form>
</body>
</html>
