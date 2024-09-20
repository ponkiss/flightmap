<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado y tiene el rol adecuado
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    // Redirigir al login si no está autenticado
    header('Location: login.html');
    exit();
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
    
    <title>Buscar | Flightmap</title>
</head>
<body>
    <!-- Encabezado con logo y navegación -->
    <header>
        <div class="logo">
            <a href="user_dashboard.php">
                <img src="../assets/images/logo.png" alt="Logo de Flightmap">
            </a>
        </div>        
        <nav>
            <a href="../index.html">Cerrar Sesión</a>
            <a href="../users/register.html">Agendar una Reservación</a>
            <a href="user_search.php">Buscar Vuelos</a>
        </nav>
    </header>

    <!-- Contenido principal de la página -->
    <main>
        <!-- Imagen de fondo decorativa -->
        <div class="background-image"></div>
        <h1>Vuelos Disponibles</h1>

        <!-- Tabla para mostrar los vuelos -->
        <table class="flight-table" id="resultados">
            <tr>
                <th>Número de Vuelo</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Salida</th>
                <th>Llegada</th>
                <th>Precio</th>
            </tr>
        </table>

        <!-- Contenedor para el mapa de Leaflet -->
        <div id="map" class="map-container"></div>
    </main>

    <!-- Pie de página con derechos reservados -->
    <footer>
        <p class="footer">&copy; 2024 TDM42. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts de Leaflet para los mapas -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const resultadosTable = document.getElementById('resultados');
        const mapContainer = document.getElementById('map');
        let map;

        // Cargar vuelos al iniciar la página
        window.onload = async () => {
            const response = await fetch('../search/search_flights.php');
            const flights = await response.json();

            // Mostrar los vuelos en la tabla
            flights.forEach(flight => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${flight.flight_number}</td>
                    <td>${flight.origin}</td>
                    <td>${flight.destination}</td>
                    <td>${flight.departure_time}</td>
                    <td>${flight.arrival_time}</td>
                    <td>${flight.price}</td>
                `;
                // Asignar un evento de clic para mostrar el mapa
                row.onclick = () => toggleMap(flight.origin, flight.destination, row);
                resultadosTable.appendChild(row);
            });
        };

        // Función para mostrar/ocultar el mapa con la distancia
        const toggleMap = async (origin, destination, selectedRow) => {
            // Si la fila ya está activa, ocultar el mapa
            if (selectedRow.classList.contains('active')) {
                selectedRow.classList.remove('active');
                mapContainer.classList.remove('active'); // Ocultar el mapa
                return;
            }

            // Remover la clase "active" de cualquier fila seleccionada anteriormente
            const rows = resultadosTable.querySelectorAll('tr');
            rows.forEach(row => row.classList.remove('active'));

            // Añadir la clase "active" a la fila seleccionada
            selectedRow.classList.add('active');
            mapContainer.classList.add('active'); // Mostrar el mapa

            // Inicializar el mapa si no está creado
            if (!map) {
                map = L.map('map').setView([0, 0], 2);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
            }

            // Obtener las coordenadas del origen y destino usando Nominatim
            const getCoordinates = async (location) => {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`);
                const data = await res.json();
                return data[0] ? [data[0].lat, data[0].lon] : null;
            };

            const originCoords = await getCoordinates(origin);
            const destinationCoords = await getCoordinates(destination);

            if (originCoords && destinationCoords) {
                // Limpiar el mapa y mostrar la nueva ruta
                map.eachLayer((layer) => {
                    if (!!layer.toGeoJSON) {
                        map.removeLayer(layer);
                    }
                });
                const route = L.polyline([originCoords, destinationCoords], { color: 'blue' }).addTo(map);
                map.fitBounds(route.getBounds()); // Ajustar vista al nuevo trayecto
            }
        };
    </script>
</body>
</html>
