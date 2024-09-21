<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si tiene el rol adecuado
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header('Location: login.html');
    exit();
}

include '../includes/db.php';

$notification = "";
$notification_type = "";

// Verificar si se ha enviado un formulario para reservar un vuelo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['flight_id'])) {
    $user_id = $_SESSION['user']['id'];
    $flight_id = $_POST['flight_id'];

    // Comprobar si el vuelo ya ha sido reservado
    $check_sql = "SELECT * FROM Reservations WHERE flight_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('i', $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Notificación si el vuelo ya fue reservado
        $notification = "Este vuelo ya ha sido reservado por otro usuario.";
        $notification_type = "error";
    } else {
        // Insertar una nueva reservación
        $sql = "INSERT INTO Reservations (user_id, flight_id) VALUES (?, ?)";
        $stmt->prepare($sql);
        $stmt->bind_param('ii', $user_id, $flight_id);

        if ($stmt->execute()) {
            // Notificación de éxito
            $notification = "Reservación exitosa.";
            $notification_type = "success";
        } else {
            // Notificación de error
            $notification = "Error al hacer la reservación: " . $stmt->error;
            $notification_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es"> <!-- Cambié a "es" ya que el contenido está en español -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <title>Vuelos y Reservaciones | Flightmap</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        
        <nav>
            <a href="../index.html">Cerrar sesión</a>
            <a href="user_search.php">Agendar una reservación</a>
            <a href="manage_reservation.php">Mis reservaciones</a>
            <a href="user_search.php">Buscar vuelos</a>
        </nav>
    </header>

    <main>
        <div class="background-image"></div>
        <h1>Vuelos y Reservaciones Disponibles</h1>

        <!-- Mostrar notificación si existe -->
        <?php if ($notification): ?>
        <div class="notification <?php echo $notification_type; ?>">
            <?php echo $notification; ?>
        </div>
        <?php endif; ?>

        <!-- Tabla de vuelos -->
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

    <!-- Scripts para cargar el mapa y manejar la lógica -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const resultadosTable = document.getElementById('resultados');
        const mapContainer = document.getElementById('map');
        let map;

        window.onload = async () => {
            const userId = <?php echo $_SESSION['user']['id']; ?>;
            const response = await fetch('../search/search_flights.php');
            const flights = await response.json();

            for (const flight of flights) {
                const row = document.createElement('tr');

                const reservationResponse = await fetch(`../search/check_reservation.php?flight_id=${flight.id}&user_id=${userId}`);
                const reservationData = await reservationResponse.json();
                const isReserved = reservationData.reserved;
                const isUserReservation = reservationData.is_user_reservation;

                row.innerHTML = `
                    <td>${flight.flight_number}</td>
                    <td>${flight.origin}</td>
                    <td>${flight.destination}</td>
                    <td>${flight.departure_time}</td>
                    <td>${flight.arrival_time}</td>
                    <td>${flight.price}</td>
                    <td>
                        ${isUserReservation ? 
                            `<a href="manage_reservation.php?flight_id=${flight.id}" class="btn">Gestionar</a>` : 
                            `<form method="POST">
                                <input type="hidden" name="flight_id" value="${flight.id}">
                                <button type="submit" ${isReserved ? 'disabled' : ''}>
                                    ${isReserved ? 'Reservado' : 'Reservar'}
                                </button>
                            </form>`
                        }
                    </td>
                `;

                // Cambiar el color si el vuelo está reservado
                if (isReserved) {
                    row.style.backgroundColor = 'lightgray';
                }

                row.onclick = () => toggleMap(flight.origin, flight.destination, row);
                resultadosTable.appendChild(row);
            }
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

            // Obtener coordenadas de origen y destino
            const getCoordinates = async (location) => {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`);
                const data = await res.json();
                return data[0] ? [data[0].lat, data[0].lon] : null;
            };

            const originCoords = await getCoordinates(origin);
            const destinationCoords = await getCoordinates(destination);

            // Mostrar la ruta en el mapa
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
