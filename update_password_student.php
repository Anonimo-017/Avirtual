<?php
include('dbcon.php');
include('session.php');

// ************************************************************************************
// WARNING: THIS CODE IS INSECURE AND SHOULD ONLY BE USED FOR TEMPORARY TESTING.
// ************************************************************************************

try {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $retype_password = $_POST['retype_password'];
    $user_id = $session_id;
    $hashed_password_from_form = $_POST['password'];

    $stmt = $pdo_conn->prepare("SELECT password FROM student WHERE student_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User not found.");
    }

    $password_from_db = $user['password'];

    if ($current_password !== $password_from_db) {
        throw new Exception("Incorrect current password.");
    }

    if ($new_password !== $retype_password) {
        throw new Exception("New passwords do not match.");
    }

    $stmt = $pdo_conn->prepare("UPDATE student SET password = :new_password WHERE student_id = :user_id");
    $stmt->bindParam(':new_password', $new_password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "success";
    } else {
        throw new Exception("Failed to update password.");
    }
} catch (Exception $e) {
    error_log("Password update error: " . $e->getMessage());

    echo "error: " . $e->getMessage();
}
