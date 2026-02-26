<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "aulavirtual";

// Conexión MySQLi
$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Error al conectar a la base de datos (MySQLi): " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

// Conexión PDO
try {
    $pdo_conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos (PDO): " . $e->getMessage());
}