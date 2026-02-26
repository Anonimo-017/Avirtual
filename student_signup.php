<?php
session_start();
include('dbcon.php');

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: signup_student.php');
	exit;
}

function escape_data($data)
{
	global $pdo_conn;
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$firstname = isset($_POST['firstname']) ? escape_data($_POST['firstname']) : '';
$lastname = isset($_POST['lastname']) ? escape_data($_POST['lastname']) : '';
$username = isset($_POST['username']) ? escape_data($_POST['username']) : '';
$class_id = isset($_POST['class_id']) ? (int)$_POST['class_id'] : 0;
$password = isset($_POST['password']) ? $_POST['password'] : '';
$cpassword = isset($_POST['cpassword']) ? $_POST['cpassword'] : '';

if (empty($firstname) || empty($lastname) || empty($username) || empty($class_id) || empty($password)) {
	echo 'Por favor, complete todos los campos.';
	exit;
}

if ($password !== $cpassword) {
	echo 'Las contraseñas no coinciden.';
	exit;
}

$stmt = $pdo_conn->prepare("SELECT class_id FROM class WHERE class_id = :class_id");
$stmt->execute(['class_id' => $class_id]);
if (!$stmt->fetch()) {
	echo 'La clase seleccionada no es válida.';
	exit;
}

try {
	$stmt = $pdo_conn->prepare("SELECT student_id FROM student WHERE username = :username");
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->execute();
	$rowCount = $stmt->rowCount();

	if ($rowCount > 0) {
		echo 'El Nro de Identificación ya existe. Por favor, elige otro.';
		exit;
	}
} catch (PDOException $e) {
	error_log("Error en la consulta de verificación: " . $e->getMessage());
	echo 'Error al verificar el usuario: ' . $e->getMessage();
	exit;
}

try {
	$stmt = $pdo_conn->prepare("
        INSERT INTO student (firstname, lastname, class_id, username, password, location, status)
        VALUES (:firstname, :lastname, :class_id, :username, :password, :location, :status)
    ");

	$location = 'uploads/NO-IMAGE-AVAILABLE.jpg';
	$status = 'Unregistered';

	$stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
	$stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
	$stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
	$stmt->bindParam(':location', $location, PDO::PARAM_STR);
	$stmt->bindParam(':status', $status, PDO::PARAM_STR);

	$stmt->execute();

	$_SESSION['student_id'] = $pdo_conn->lastInsertId();
	$_SESSION['success_message'] = "Registro exitoso. ¡Bienvenido!";
	header("Location: index.php");
	exit;
} catch (PDOException $e) {
	error_log("Error en la consulta de inserción: " . $e->getMessage());
	echo 'Error al registrar el usuario: ' . $e->getMessage();
	exit;
}