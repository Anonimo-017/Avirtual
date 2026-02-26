<?php
session_start();
include('dbcon.php');
//variables 
$username = $_POST['username'];
$password = $_POST['password'];

//prevencion la inyección SQL
$username = mysqli_real_escape_string($con, $username);
$password = mysqli_real_escape_string($con, $password);

// Consulta SQL para busacar al usario
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_array($result);

if ($row) {
    // Verificar la contraseña 
    if ($password == $row['password']) { 
        $_SESSION['id'] = $row['user_id'];

        // Insertar registro de inicio de sesión en la tabla user_log
        $user_id = $row['user_id'];
        $query = "INSERT INTO user_log (username, login_date, user_id) VALUES ('$username', NOW(), '$user_id')";
        mysqli_query($con, $query) or die(mysqli_error($con));

        echo 'true';
    } else {
        echo 'false'; 
    }
} else {
    echo 'false'; 
}

mysqli_close($con);
?>