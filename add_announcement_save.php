<?php
include('admin/dbcon.php');
include('session.php');

$content = isset($_POST['content']) ? mysqli_real_escape_string($con, $_POST['content']) : '';
$id = isset($_POST['selector']) ? $_POST['selector'] : array();

if (empty($content) || empty($id)) {
	die("Error: Content and/or selector not provided.");
}
 
$N = count($id);
for ($i = 0; $i < $N; $i++) {
	$teacher_class_id = intval($id[$i]);

	$content = mysqli_real_escape_string($con, $content);

	$query1 = "INSERT INTO teacher_class_announcements (teacher_class_id, teacher_id, content, date) VALUES ('$teacher_class_id', '$session_id', '$content', NOW())";
	$result1 = mysqli_query($con, $query1) or die(mysqli_error($con));

	$query2 = "INSERT INTO notification (teacher_class_id, notification, date_of_notification, link) VALUES ('$teacher_class_id', 'Add Annoucements', NOW(), 'announcements_student.php')";
	$result2 = mysqli_query($con, $query2) or die(mysqli_error($con));
}

echo "Se a notificado alos alumnos";