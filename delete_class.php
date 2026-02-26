<?php
include('admin/dbcon.php');

if (isset($_POST['id'])) {
    $get_id = $_POST['id'];
    $get_id = mysqli_real_escape_string($con, $get_id);

    $queries = [
        "DELETE FROM teacher_class WHERE teacher_class_id = '$get_id'",
        "DELETE FROM teacher_class_student WHERE teacher_class_id = '$get_id'",
        "DELETE FROM teacher_class_announcements WHERE teacher_class_id = '$get_id'",
        "DELETE FROM teacher_notification WHERE teacher_class_id = '$get_id'",
        "DELETE FROM class_subject_overview WHERE teacher_class_id = '$get_id'"
    ];

    $success = true; 

    foreach ($queries as $query) {
        $result = mysqli_query($con, $query);
        if (!$result) {
            $success = false;
            echo "Error deleting from table: " . mysqli_error($con); 
            break; 
        }
    }

    if ($success) {
        echo "success"; 
    }

} else {
    echo "Error: ID not provided.";
}
?>