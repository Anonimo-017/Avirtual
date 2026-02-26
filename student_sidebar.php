<?php
include('dbcon.php');

if (!isset($session_id) || !is_numeric($session_id)) {
    die('Error: estudiante no identificado.');
}

$stmt_student = $pdo_conn->prepare("
    SELECT firstname, lastname, location
    FROM student
    WHERE student_id = :student_id
    LIMIT 1
");

$stmt_student->execute(['student_id' => (int)$session_id]);
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

$avatar    = !empty($student['location']) ? $student['location'] : 'uploads/uploads/avatar.png';
$firstname = isset($student['firstname']) ? htmlspecialchars($student['firstname'], ENT_QUOTES, 'UTF-8') : 'Estudiante';
$lastname  = isset($student['lastname']) ? htmlspecialchars($student['lastname'], ENT_QUOTES, 'UTF-8') : '';

$stmt_notif = $pdo_conn->prepare("
    SELECT COUNT(*) AS not_read
    FROM notification n
    LEFT JOIN notification_read nr
        ON n.notification_id = nr.notification_id AND nr.student_id = :student_id
    WHERE nr.notification_id IS NULL
");
$stmt_notif->execute(['student_id' => (int)$session_id]);
$notif_row = $stmt_notif->fetch(PDO::FETCH_ASSOC);
$not_read = $notif_row ? (int)$notif_row['not_read'] : 0;

$stmt_msg = $pdo_conn->prepare("
    SELECT COUNT(*) AS count_message
    FROM message
    WHERE reciever_id = :student_id AND message_status != 'read'
");
$stmt_msg->execute(['student_id' => (int)$session_id]);
$msg_row = $stmt_msg->fetch(PDO::FETCH_ASSOC);
$count_message = $msg_row ? (int)$msg_row['count_message'] : 0;
?>

<div class="span3" id="sidebar">
    <img id="avatar" class="img-polaroid" src="<?php echo htmlspecialchars($avatar); ?>" alt="Foto de Perfil">

    <?php include('count.php');
    ?>

    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class="active">
            <a href="dashboard_student.php">
                <i class="icon-chevron-right"></i><i class="icon-group"></i>&nbsp;Mi Clase
            </a>
        </li>

</div>