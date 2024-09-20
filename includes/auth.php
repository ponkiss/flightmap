<?php
include 'db.php'; // Conexión a la base de datos

// Función de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'register') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptación de la contraseña
    $email = $_POST['email'];

    $sql = "INSERT INTO Users (username, password, email) VALUES ('$username', '$password', '$email')";
    if (mysqli_query($conn, $sql)) {
        echo "Usuario registrado con éxito";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Función de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Iniciar sesión y almacenar datos del usuario
        echo "Login exitoso";
    } else {
        echo "Usuario o contraseña incorrecta";
    }
}

// Función de Admin
if ($user && password_verify($password, $user['password'])) {
    if ($user['role'] == 'admin') {
        // Redirigir al panel de administración
        header('Location: admin_dashboard.php');
        exit();
    } else {
        // Redirigir al área de usuario normal
        header('Location: user_dashboard.php');
        exit();
    }
} else {
    echo "Usuario o contraseña incorrecta";
}

?>
