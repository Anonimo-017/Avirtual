<?php
require_once('admin/dbcon.php');

if (isset($_POST['delete_event'])) {
    $get_id = isset($_POST['get_id']) ? intval($_POST['get_id']) : 0;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($get_id <= 0 || $id <= 0) {
        echo "Invalid input.";
        exit;
    }

    $sql = "DELETE FROM event WHERE event_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id); 

        if (mysqli_stmt_execute($stmt)) {
            header("Location: class_calendar.php?id=" . urlencode($get_id));
            exit;
        } else {
            echo "Error deleting event: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
    mysqli_close($con);
} else {
    echo "Invalid request.";
}