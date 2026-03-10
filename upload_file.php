<?php
include('db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = intval($_POST['class_id']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $fdesc = mysqli_real_escape_string($con, $_POST['fdesc']);
    $uploaded_by = $_SESSION['username'];
    $file = $_FILES['file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = basename($file['name']);
        $targetFile = $uploadDir . uniqid() . '_' . $filename;
        move_uploaded_file($file['tmp_name'], $targetFile);

        $query = "INSERT INTO files (fname, fdesc, floc, class_id, uploaded_by, fdatein)
                  VALUES ('$fname', '$fdesc', '$targetFile', '$class_id', '$uploaded_by', NOW())";
        mysqli_query($con, $query) or die(mysqli_error($con));
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        echo "Error al subir archivo.";
    }
}