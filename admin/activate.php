<?php
include('dbcon.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);

    $query = mysqli_query($con, "UPDATE teacher SET teacher_stat = 'Activated' WHERE teacher_id = '$id'") or die(mysqli_error($con));

    if ($query) {
        header("location: teachers.php");
        exit();
    } else {
        echo "Error al activar el profesor: " . mysqli_error($con);
    }
} else {
    echo "Error: No se proporcionó el ID del profesor.";
}
?>