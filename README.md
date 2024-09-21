<h2 align="center">✈️ flightmap ✈️</h2>

---

### 🚀 Descripción
Este proyecto es una plataforma web simple para la consulta y agendación de reservaciones de vuelos. Los usuarios pueden buscar vuelos y hacer reservaciones, y los administradores pueden gestionar tanto vuelos como reservaciones.

---

### 🎨 Características
- 🔍 **Buscar Vuelos:** Los usuarios pueden consultar la disponibilidad de vuelos según criterios como destino y fecha.
- 📝 **Reservar Vuelos:** Los usuarios pueden agendar nuevas reservaciones de vuelos.
- 🛠️ **Panel de Administrador:** Los administradores pueden gestionar los vuelos y las reservaciones.

---

### 🛠️ Tecnologías Usadas
![HTML5](https://img.shields.io/badge/-HTML5-E34F26?logo=html5&logoColor=fff&style=for-the-badge)
![CSS3](https://img.shields.io/badge/-CSS3-1572B6?logo=css3&logoColor=fff&style=for-the-badge)
![PHP](https://img.shields.io/badge/-PHP-777BB4?logo=php&logoColor=fff&style=for-the-badge)
![MySQL](https://img.shields.io/badge/-MySQL-4479A1?logo=mysql&logoColor=fff&style=for-the-badge)

---

### 🔧 Configuración e Instalación
1. Clona el repositorio:
   ```bash
   git clone https://github.com/ponkiss/flightmap.git
   ```
2. Navega al directorio del proyecto:
   ```bash
   cd flightmap
   ```
3. Configura tu servidor local (ej. XAMPP) y mueve los archivos al directorio adecuado (ej. `htdocs` en XAMPP).
4. Configura la base de datos importando el archivo SQL provisto en el directorio `db`.
5. Abre el navegador y navega a `http://localhost/flightmap`.

---

### 📂 Estructura del Proyecto
```
.
├── assets
│   ├── css              # Archivos de estilos CSS
│   └── images           # Imágenes utilizadas en la web
├── flights
│   ├── manage_flights.php          # Gestión de vuelos (administrador)
│   └── manage_reservations.php     # Gestión de reservaciones (administrador)
├── includes
│   ├── auth.php                    # Manejo de autenticación
│   └── db.php                      # Conexión a la base de datos
├── search
│   ├── check_reservation.php        # Verificación de reservaciones
│   ├── search_flights.php           # Búsqueda de vuelos
│   └── search.html                  # Página para búsqueda de vuelos
├── users
│   ├── admin_dashboard.php          # Panel de control del administrador
│   ├── login.html                   # Página de inicio de sesión
│   ├── manage_reservation.php       # Gestión de reservaciones
│   ├── register.html                # Página de registro de usuarios
│   ├── user_dashboard.php           # Panel de control del usuario
│   └── user_search.php              # Página para búsqueda de vuelos (usuario)
├── flight_reservations.sql
├── index.html
├── LICENSE
└── README.md
```

---

### 📝 To-Do List
- 🔄 Crear una interfaz más amigable para la gestión de vuelos.

---

### 🤝 Contribuciones
Siéntete libre de hacer un fork de este repositorio, abrir incidencias o enviar pull requests para mejorar el sistema!

---
