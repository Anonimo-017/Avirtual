<?php
session_start();
include('admin/dbcon.php');

$username = $_POST['username'];
$password = $_POST['password'];

$username = mysqli_real_escape_string($con, $username);
$password = mysqli_real_escape_string($con, $password);

$query = "SELECT student_id FROM student WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_array($result);
$num_row = mysqli_num_rows($result);

if ($num_row > 0) {
    $_SESSION['id'] = $row['student_id'];
    $student_id = intval($row['student_id']);
    $login_time = date('Y-m-d H:i:s');

    $insert_query = "INSERT INTO student_log (student_id, login_time) VALUES ('$student_id', '$login_time')";
    mysqli_query($con, $insert_query) or die(mysqli_error($con));

    echo 'true_student';
} else {
    $query_teacher = "SELECT teacher_id FROM teacher WHERE username = '$username' AND password = '$password'";
    $query_teacher_result = mysqli_query($con, $query_teacher) or die(mysqli_error($con));
    $num_row_teacher = mysqli_num_rows($query_teacher_result);
    $row_teacher = mysqli_fetch_array($query_teacher_result);

    if ($num_row_teacher > 0) {
        $_SESSION['id'] = $row_teacher['teacher_id'];
        $teacher_id = intval($row_teacher['teacher_id']);
        $login_time = date('Y-m-d H:i:s');

        $insert_query = "INSERT INTO teacher_log (teacher_id, login_time) VALUES ('$teacher_id', '$login_time')";
        mysqli_query($con, $insert_query) or die(mysqli_error($con));

        echo 'true';
    } else {
        echo 'false';
    }
}

mysqli_close($con);