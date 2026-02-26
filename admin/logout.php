<?php
include('dbcon.php');
include('session.php');

$session_id = mysqli_real_escape_string($con, $session_id);

$query = mysqli_query($con, "UPDATE user_log SET logout_Date = NOW() WHERE user_id = '$session_id'") or die(mysqli_error($con));

session_destroy();

header('location:index.php');