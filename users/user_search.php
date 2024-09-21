<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado y tiene el rol adecuado
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header('Location: login.html');
    exit();
}

include '../includes/db.php'; // Conexión a la base de datos

// Si se hace una reserva
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['flight_id'])) {
    $user_id = $_SESSION['user']['id'];
    $flight_id = $_POST['flight_id'];

    // Verificar si el vuelo ya fue reservado por otro usuario
    $check_sql = "SELECT * FROM Reservations WHERE flight_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('i', $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p>Este vuelo ya ha sido reservado por otro usuario.</p>";
    } else {
        // Insertar la reservación en la base de datos
        $sql = "INSERT INTO Reservations (user_id, flight_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $flight_id);

        if ($stmt->execute()) {
            echo "<p>Reservación exitosa.</p>";
        } else {
            echo "<p>Error al hacer la reservación: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <title>Reservaciones | Flightmap</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        
        <nav>
            <a href="../index.html">Cerrar Sesión</a>
            <a href="user_search.php">Agendar una Reservación</a>
            <a href="user_search.php">Buscar Vuelos</a>
        </nav>
    </header>

    <main>
        <h1>Vuelos Disponibles</h1>
        <table class="flight-table" id="resultados">
            <tr>
                <th>Número de Vuelo</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Salida</th>
                <th>Llegada</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </table>

        <div id="map" class="map-container"></div>
    </main>

    <footer>
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const resultadosTable = document.getElementById('resultados');
        const mapContainer = document.getElementById('map');
        let map;

        window.onload = async () => {
            const response = await fetch('../search/search_flights.php');
            const flights = await response.json();

            flights.forEach(flight => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${flight.flight_number}</td>
                    <td>${flight.origin}</td>
                    <td>${flight.destination}</td>
                    <td>${flight.departure_time}</td>
                    <td>${flight.arrival_time}</td>
                    <td>${flight.price}</td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="flight_id" value="${flight.id}">
                            <button type="submit">Reservar</button>
                        </form>
                    </td>
                `;
                row.onclick = () => toggleMap(flight.origin, flight.destination, row);
                resultadosTable.appendChild(row);
            });
        };

        const toggleMap = async (origin, destination, selectedRow) => {
            if (selectedRow.classList.contains('active')) {
                selectedRow.classList.remove('active');
                mapContainer.classList.remove('active');
                return;
            }

            const rows = resultadosTable.querySelectorAll('tr');
            rows.forEach(row => row.classList.remove('active'));

            selectedRow.classList.add('active');
            mapContainer.classList.add('active');

            if (!map) {
                map = L.map('map').setView([0, 0], 2);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
            }

            const getCoordinates = async (location) => {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`);
                const data = await res.json();
                return data[0] ? [data[0].lat, data[0].lon] : null;
            };

            const originCoords = await getCoordinates(origin);
            const destinationCoords = await getCoordinates(destination);

            if (originCoords && destinationCoords) {
                map.eachLayer((layer) => {
                    if (!!layer.toGeoJSON) {
                        map.removeLayer(layer);
                    }
                });
                const route = L.polyline([originCoords, destinationCoords], { color: 'blue' }).addTo(map);
                map.fitBounds(route.getBounds());
            }
        };
    </script>
</body>
</html>
