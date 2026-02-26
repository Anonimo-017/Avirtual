<?php
include('dbcon.php');

if (isset($_POST['backup_delete'])) {
	$ids = $_POST['selector'] ?? [];
	$get_id = $_POST['get_id'] ?? '';

	if (!empty($ids) && is_array($ids)) {
		$placeholders = rtrim(str_repeat('?,', count($ids)), ',');
		$stmt = $pdo_conn->prepare("DELETE FROM quiz_question WHERE quiz_question_id IN ($placeholders)");

		$stmt->execute($ids);
	}

	echo '<script>window.location = "quiz_question.php?id=' . htmlspecialchars($get_id) . '";</script>';
}