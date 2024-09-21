<?php
session_start(); // Inicia la sesión
include 'db.php'; // Incluye el archivo de conexión a la base de datos

// Registro de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'register') {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Escapa caracteres especiales en el nombre de usuario
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash de la contraseña
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Escapa caracteres especiales en el correo electrónico
    $role = 'user'; // Rol por defecto

    // Preparar la consulta SQL para insertar un nuevo usuario
    $stmt = $conn->prepare("INSERT INTO Users (username, password, email, role) VALUES (?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $password, $email, $role); // Vincula los parámetros
        
        if ($stmt->execute()) {
            header("Location: ../index.html"); // Redirige a la página principal después del registro
            exit();
        } else {
            echo "Error al registrar el usuario: " . $stmt->error; // Mensaje de error
        }
        
        $stmt->close(); // Cierra la declaración
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error; // Mensaje de error
    }
}

// Inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'login') {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Escapa caracteres especiales en el nombre de usuario
    $password = $_POST['password']; // Contraseña sin hash

    // Preparar la consulta SQL para verificar el usuario
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $username); // Vincula el parámetro
        
        $stmt->execute(); // Ejecuta la consulta
        $result = $stmt->get_result(); // Obtiene el resultado
        $user = $result->fetch_assoc(); // Recupera el usuario
        
        // Verifica las credenciales
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            
            // Redirige según el rol del usuario
            if ($user['role'] == 'admin') {
                header('Location: ../users/admin_dashboard.php'); // Redirige al panel de administración
            } else {
                header('Location: ../users/user_dashboard.php'); // Redirige al panel de usuario
            }
            exit();
        } else {
            echo "Usuario o contraseña incorrecta"; // Mensaje de error
        }

        $stmt->close(); // Cierra la declaración
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error; // Mensaje de error
    }
}

// Cierre de sesión
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy(); // Destruye la sesión
    header('Location: ../users/login.html'); // Redirige a la página de inicio de sesión
    exit();
}
?>
