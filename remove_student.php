<?php
require_once('admin/dbcon.php');
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id <= 0) {
        echo "Invalid ID.";
        exit;
    }
    $sql = "DELETE FROM teacher_class_student WHERE teacher_class_student_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Student removed from class.";
        } else {
            echo "Error removing student from class: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
    mysqli_close($con);
} else {
    echo "ID not provided.";
}
