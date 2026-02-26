<?php
include('dbcon.php');

if (isset($_POST['backup_delete'])) {
	$get_id = $_GET['id'] ?? null;
	$ids = $_POST['selector'] ?? [];

	if (!empty($ids) && is_array($ids)) {
		$placeholders = rtrim(str_repeat('?,', count($ids)), ',');
		$stmt = $pdo_conn->prepare("DELETE FROM class_quiz WHERE class_quiz_id IN ($placeholders)");

		$stmt->execute($ids);
	}

	echo '<script>window.location = "class_quiz.php?id=' . htmlspecialchars($get_id) . '";</script>';
}