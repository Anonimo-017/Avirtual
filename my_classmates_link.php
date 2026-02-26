<?php
$get_id = isset($get_id) ? (int)$get_id : 0;

$local_avatar_path = 'admin/images/logo-utt.png' . (isset($row['location']) && !empty($row['location']) ? $row['location'] : '');

if (file_exists($local_avatar_path) && is_readable($local_avatar_path)) {
    $avatar = $local_avatar_path;
} else {
    $avatar = $default_avatar;
}
?>

<div class="span3" id="sidebar">
    <img id="avatar" src="<?php echo htmlspecialchars($avatar); ?>" class="img-polaroid">

    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class="">
            <a href="dashboard_student.php">
                <i class="icon-chevron-right"></i><i class="icon-chevron-left"></i>&nbsp;Volver
            </a>
        </li>
        <li class="active">
            <a href="my_classmates.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-group"></i>&nbsp;Mi clase
            </a>
        </li>
        <li class="">
            <a href="progress.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-bar-chart"></i>&nbsp;Mi progreso
            </a>
        </li>
        <li class="">
            <a href="subject_overview_student.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-file"></i>&nbsp;Descripción de Asignatura
            </a>
        </li>
        <li class="">
            <a href="downloadable_student.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-download"></i>&nbsp;Tareas
            </a>
        </li>
        <li class="">
            <a href="assignment_student.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-book"></i>&nbsp;Plan de estudios
            </a>
        </li>
        <li class="">
            <a href="announcements_student.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-info-sign"></i>&nbsp;Avisos
            </a>
        </li>
        <li class="">
            <a href="class_calendar_student.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-calendar"></i>&nbsp;Horario de Clases
            </a>
        </li>
        <li class="">
            <a href="student_quiz_list.php?id=<?php echo $get_id; ?>">
                <i class="icon-chevron-right"></i><i class="icon-reorder"></i>&nbsp;Examenes
            </a>
        </li>
    </ul>

</div>