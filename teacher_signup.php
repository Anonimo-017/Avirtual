<?php
include('admin/dbcon.php');
session_start();

$username = mysqli_real_escape_string($con, $_POST['username']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$firstname = mysqli_real_escape_string($con, $_POST['firstname']);
$lastname = mysqli_real_escape_string($con, $_POST['lastname']);
$department_id = mysqli_real_escape_string($con, $_POST['department_id']);

$query = mysqli_query($con, "SELECT * FROM teacher WHERE firstname='$firstname' AND lastname='$lastname' AND department_id = '$department_id'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);
$count = mysqli_num_rows($query);

if ($count > 0) {
    $id = $row['teacher_id'];
    mysqli_query($con, "UPDATE teacher SET username='$username', password='$password', teacher_status='Registered' WHERE teacher_id = '$id'") or die(mysqli_error($con));
    $_SESSION['id'] = $id;
    echo 'true'; 
} else {

    $insert_query = mysqli_query($con, "INSERT INTO teacher (firstname, lastname, department_id, username, password, teacher_status) VALUES ('$firstname', '$lastname', '$department_id', '$username', '$password', 'Registered')") or die(mysqli_error($con));

    if ($insert_query) {
        $id = mysqli_insert_id($con); 
        $_SESSION['id'] = $id;
        echo 'true'; 
    } else {
        echo 'false';
    }
}
?>