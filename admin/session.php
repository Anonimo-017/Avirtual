<?php
include('dbcon.php');
session_start();

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) { ?>
<script>
window.location = "index.php";
</script>
<?php
    exit();
}

$session_id = $_SESSION['id'];

$user_query = mysqli_query($con, "SELECT * FROM users WHERE user_id = '$session_id'") or die(mysqli_error($con));
$user_row = mysqli_fetch_array($user_query);

if ($user_row) {
    $user_username = htmlspecialchars($user_row['username'], ENT_QUOTES, 'UTF-8');
} else {
    $user_username = "Usuario no encontrado";
}
?>