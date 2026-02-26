<?php
include('dbcon.php');

if (isset($_POST['un']) && isset($_POST['fn']) && isset($_POST['ln']) && isset($_POST['class_id'])) {
    $un = mysqli_real_escape_string($con, $_POST['un']);
    $fn = mysqli_real_escape_string($con, $_POST['fn']);
    $ln = mysqli_real_escape_string($con, $_POST['ln']);
    $class_id = mysqli_real_escape_string($con, $_POST['class_id']);

    if (empty($un) || empty($fn) || empty($ln) || empty($class_id)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    if (strlen($un) > 20 || strlen($fn) > 50 || strlen($ln) > 50) {
        echo "Error: La longitud de los campos excede el límite permitido.";
        exit;
    }

    $query = mysqli_query($con, "SELECT * FROM student WHERE username = '$un'") or die(mysqli_error($con));
    $count = mysqli_num_rows($query);

    if ($count > 0) {
        echo "Error: El estudiante ya existe.";
        exit;
    }

    mysqli_query($con, "insert into student (username,firstname,lastname,location,class_id,status)
    values ('$un','$fn','$ln','images/gorchor.png','$class_id','Unregistered')") or die(mysqli_error($con));

    mysqli_query($con, "INSERT INTO activity_log (date,username,action) VALUES(NOW(),'$user_username','Agregar estudiante $un')") or die(mysqli_error($con));

    echo "success";
} else {
    echo "Error: No se recibieron los datos del formulario.";
}