<?php
include('dbcon.php');

$stmt_year = $pdo_conn->prepare("SELECT school_year FROM school_year ORDER BY school_year DESC LIMIT 1");
$stmt_year->execute();
$school_year_row = $stmt_year->fetch(PDO::FETCH_ASSOC);
$school_year = $school_year_row ? $school_year_row['school_year'] : null;

$stmt_notif = $pdo_conn->prepare("
    SELECT COUNT(DISTINCT n.notification_id) AS total_notifications
    FROM teacher_class_student tcs
    JOIN teacher_class tc ON tc.teacher_class_id = tcs.teacher_class_id
    JOIN notification n ON n.teacher_class_id = tc.teacher_class_id
    WHERE tcs.student_id = :student_id
    AND tc.school_year = :school_year
");
$stmt_notif->execute([
	'student_id' => (int)$session_id,
	'school_year' => $school_year
]);
$notif_row = $stmt_notif->fetch(PDO::FETCH_ASSOC);
$count_no = $notif_row ? (int)$notif_row['total_notifications'] : 0;

$stmt_read = $pdo_conn->prepare("
    SELECT COUNT(*) AS read_notifications
    FROM notification_read
    WHERE student_id = :student_id
");
$stmt_read->execute(['student_id' => (int)$session_id]);
$read_row = $stmt_read->fetch(PDO::FETCH_ASSOC);
$count_yes = $read_row ? (int)$read_row['read_notifications'] : 0;

$not_read = max(0, $count_no - $count_yes);