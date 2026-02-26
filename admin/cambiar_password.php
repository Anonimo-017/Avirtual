<?php
include('general/session.php');
include('bdconf/dbcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_SESSION['id'];
  $current_password = mysqli_real_escape_string($con, $_POST['current_password']);
  $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
  $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

  $query = mysqli_query($con, "SELECT password FROM users WHERE user_id = '$user_id'") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query);
  $hashed_password = $row['password'];

  if (!password_verify($current_password, $hashed_password)) {
    header("Location: perfil.php?password_error=1");
    exit();
  }

  if ($new_password !== $confirm_password) {
    header("Location: perfil.php?password_mismatch=1");
    exit();
  }

  $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

  $update_query = "UPDATE users SET password = '$hashed_new_password' WHERE user_id = '$user_id'";

  if (mysqli_query($con, $update_query)) {
    header("Location: perfil.php?password_success=1");
    exit();
  } else {
    header("Location: perfil.php?password_update_error=1");
    exit();
  }
} else {
  header("Location: perfil.php");
  exit();
}