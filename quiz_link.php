<div class="span3" id="sidebar">
    <img id="avatar" src="<?php echo $row['location']; ?>" class="img-polaroid">
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class=""><a href="dashboard_teacher.php"><i class="icon-chevron-right"></i><i
                    class="icon-chevron-left"></i>&nbsp;Volver</a></li>
        <li class=""><a href="my_students.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-group"></i>&nbsp;Mis Estudiantes</a></li>
        <li class=""><a href="downloadable.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-download"></i>&nbsp;Tareas</a></li>
        <li class=""><a href="assignment.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-book"></i>&nbsp;Plan de estudios</a></li>
        <li class=""><a href="announcements.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-info-sign"></i>&nbsp;Avisos</a></li>
        <li class=""><a href="class_calendar.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-calendar"></i>&nbsp;Horario de Clases</a></li>
        <li class="active"><a href="class_quiz.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-list"></i>&nbsp;Examenes</a></li>
    </ul>
</div>