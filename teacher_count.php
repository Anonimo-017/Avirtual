<?php
include('admin/dbcon.php');

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

$school_year_query = mysqli_query($con, "SELECT * FROM school_year ORDER BY school_year DESC") or die(mysqli_error($con));
$school_year_query_row = mysqli_fetch_array($school_year_query);
$school_year = $school_year_query_row['school_year'];
?>

<?php
$query_yes = mysqli_query($con, "SELECT * FROM teacher_notification
LEFT JOIN notification_read_teacher ON teacher_notification.teacher_notification_id = notification_read_teacher.notification_id
WHERE teacher_id = '$session_id'") or die(mysqli_error($con));
$count_no = mysqli_num_rows($query_yes);
?>

<?php
$query = mysqli_query($con, "SELECT * FROM teacher_notification
LEFT JOIN teacher_class ON teacher_class.teacher_class_id = teacher_notification.teacher_class_id
LEFT JOIN student ON student.student_id = teacher_notification.student_id
LEFT JOIN assignment ON assignment.assignment_id = teacher_notification.assignment_id
LEFT JOIN class ON teacher_class.class_id = class.class_id
LEFT JOIN subject ON teacher_class.subject_id = subject.subject_id
WHERE teacher_class.teacher_id = '$session_id'") or die(mysqli_error($con));
$count = mysqli_num_rows($query);
?>

<?php
$not_read = $count -  $count_no;
?>