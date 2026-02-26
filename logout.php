<?php
session_start();
include('admin/dbcon.php');

if (isset($_SESSION['id'])) {
    $user_id = intval($_SESSION['id']); 

    $query_student = "SELECT student_id FROM student WHERE student_id = '$user_id'";
    $result_student = mysqli_query($con, $query_student);

    if (mysqli_num_rows($result_student) > 0) {
        $user_type = 'student';
        $log_table = 'student_log';
        $id_field = 'student_id';
    } else {
        $user_type = 'teacher';
        $log_table = 'teacher_log';
        $id_field = 'teacher_id';
    }

    $logout_time = date('Y-m-d H:i:s');

    $update_query = "UPDATE $log_table SET logout_time = '$logout_time' WHERE $id_field = '$user_id' AND logout_time IS NULL";
    mysqli_query($con, $update_query) or die(mysqli_error($con));

    session_destroy();
    header('location:index.php');
} else {
    header('location:index.php');
}