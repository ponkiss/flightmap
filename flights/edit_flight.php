<?php
include 'db.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Flights WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $flight = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_number = $_POST['flight_number'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    $sql = "UPDATE Flights SET flight_number='$flight_number', origin='$origin', destination='$destination', 
            departure_time='$departure_time', arrival_time='$arrival_time', price='$price' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "Vuelo actualizado con éxito";
        header('Location: manage_flights.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Vuelo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Vuelo</h1>
    <form action="" method="POST">
        <input type="text" name="flight_number" value="<?php echo $flight['flight_number']; ?>" required><br>
        <input type="text" name="origin" value="<?php echo $flight['origin']; ?>" required><br>
        <input type="text" name="destination" value="<?php echo $flight['destination']; ?>" required><br>
        <input type="datetime-local" name="departure_time" value="<?php echo $flight['departure_time']; ?>" required><br>
        <input type="datetime-local" name="arrival_time" value="<?php echo $flight['arrival_time']; ?>" required><br>
        <input type="number" step="0.01" name="price" value="<?php echo $flight['price']; ?>" required><br>
        <button type="submit">Actualizar Vuelo</button>
    </form>
</body>
</html>
