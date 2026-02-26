<?php
session_start(); // Ensure session is started if you use it
include('dbcon.php');

// Sanitize the input data
$session_id = mysqli_real_escape_string($con, $_POST['session_id']);
$subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
$class_id = mysqli_real_escape_string($con, $_POST['class_id']);
$school_year = mysqli_real_escape_string($con, $_POST['school_year']);

$query = mysqli_query($con, "SELECT * FROM teacher_class WHERE subject_id = '$subject_id' AND class_id = '$class_id' AND teacher_id = '$session_id' AND school_year = '$school_year'") or die(mysqli_error($con));
$count = mysqli_num_rows($query);

if ($count > 0) {
    echo "class_exists"; 
} else {
    $insert_query = "INSERT INTO teacher_class (teacher_id, subject_id, class_id, thumbnails, school_year) VALUES ('$session_id', '$subject_id', '$class_id', 'admin/uploads/thumbnails.jpg', '$school_year')";
    if (mysqli_query($con, $insert_query)) {
        $teacher_class_id = mysqli_insert_id($con); 

        $insert_query = mysqli_query($con, "SELECT * FROM student WHERE class_id = '$class_id'") or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($insert_query)) {
            $student_id = mysqli_real_escape_string($con, $row['student_id']); 
            mysqli_query($con, "INSERT INTO teacher_class_student (teacher_id, student_id, teacher_class_id) VALUES ('$session_id', '$student_id', '$teacher_class_id')") or die(mysqli_error($con));
        }
        echo "success"; 
    } else {
        echo "insert_error"; 
    }
}
?>