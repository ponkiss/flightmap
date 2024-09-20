<?php
session_start();
include 'db.php';

// Función de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'register') {
    // Recibe los valores del formulario de manera segura
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hasheo seguro
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = 'user';

    // Prepara la sentencia SQL para evitar inyección de SQL
    $stmt = $conn->prepare("INSERT INTO Users (username, password, email, role) VALUES (?, ?, ?, ?)");
    
    if ($stmt) {
        // Vincula los parámetros a la consulta
        $stmt->bind_param("ssss", $username, $password, $email, $role);
        
        // Ejecuta la consulta
        if ($stmt->execute()) {
            // Redirige al usuario si el registro fue exitoso
            header("Location: ../index.html");
            exit();  // Detiene el script después de la redirección
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
        
        // Cierra la sentencia
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

// Función de login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'login') {
    // Recibe los valores del formulario de manera segura
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Prepara la sentencia SQL para evitar inyección de SQL
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    
    if ($stmt) {
        // Vincula los parámetros a la consulta
        $stmt->bind_param("s", $username);
        
        // Ejecuta la consulta
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Verifica la contraseña
        if ($user && password_verify($password, $user['password'])) {
            // Guardar la información del usuario en la sesión
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            
            // Redirige según el rol del usuario
            if ($user['role'] == 'admin') {
                header('Location: ../users/admin_dashboard.php');
            } else {
                header('Location: ../users/user_dashboard.php');
            }
            exit();
        } else {
            echo "Usuario o contraseña incorrecta";
        }

        // Cierra la sentencia
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

// Función de logout
if ($_GET['action'] == 'logout') {
    session_destroy();
    header('Location: ../users/login.html');
    exit();
}
?>
