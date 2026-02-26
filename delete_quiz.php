<?php
include('dbcon.php');

if (isset($_POST['backup_delete'])) {
	$id = $_POST['selector'];
	$N = count($id);

	for ($i = 0; $i < $N; $i++) {

		$result = mysqli_query($con, "DELETE FROM quiz WHERE quiz_id='" . mysqli_real_escape_string($con, $id[$i]) . "'");

		if (!$result) {
			error_log("MySQL Delete Error: " . mysqli_error($con));
			echo "Error deleting quiz with ID: " . htmlspecialchars($id[$i]) . "<br>";
		}
	}

	header("location: teacher_quiz.php");
	exit;
}
