<?php
include('general/session.php');
include('bdconf/dbcon.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_SESSION['id'];
  $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
  $username = mysqli_real_escape_string($con, $_POST['username']);

  $query = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', username = '$username' WHERE user_id = '$user_id'";

  if (mysqli_query($con, $query)) {
    header("Location: perfil.php?success=1");
    exit();
  } else {
    header("Location: perfil.php?error=1");
    exit();
  }
} else {

  header("Location: perfil.php");
  exit();
}
?>