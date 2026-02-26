<?php
include('dbcon.php');
include('session.php');

if (isset($_POST['upload'])) {

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error_message'] = "Tipo de archivo no válido. Solo se permiten imágenes JPG, JPEG, PNG y GIF.";
        header("Location: dashboard_teacher.php");
        exit;
    }

    $prefix = "profile_image_" . time();
    $image = $prefix . "." . $file_ext;

    $location = "admin/uploads/" . $image;

    if (!move_uploaded_file($image_tmp, $location)) {
        $_SESSION['error_message'] = "Error al subir la imagen. Inténtalo de nuevo.";
        header("Location: dashboard_teacher.php");
        exit;
    }

    $session_id = mysqli_real_escape_string($con, $session_id);
    $location = mysqli_real_escape_string($con, $location);

    mysqli_query($con, "UPDATE teacher SET location = '$location' WHERE teacher_id = '$session_id'") or die(mysqli_error($con));

    $_SESSION['success_message'] = "Imagen de perfil actualizada correctamente.";
    header("Location: dashboard_teacher.php");
    exit;
}