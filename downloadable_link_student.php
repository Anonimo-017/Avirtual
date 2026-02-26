<div class="span3" id="sidebar">
    <?php

    $student_query = mysqli_query($con, "SELECT * FROM student WHERE student_id = '$session_id'") or die(mysqli_error($con));
    $student_row = mysqli_fetch_array($student_query);

    if ($student_row && isset($student_row['location'])) {
        $location = $student_row['location'];
        $location_safe = ($location !== null) ? htmlspecialchars($location, ENT_QUOTES, 'UTF-8') : "";
        if (!empty($location_safe)) {
            echo '<img id="avatar" src="admin/images/logo-utt.png">';
        } else {
            echo '<img id="avatar" src="admin/images/logo-utt.png" class="img-polaroid">';
            echo "<p>Sin foto de perfil</p>";
        }
    } else {
        echo '<img id="avatar" src="admin/uploads/default_avatar.png" class="img-polaroid">';
        echo "<p>Sin foto de perfil</p>";
    }
    ?>
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class=""><a href="dashboard_student.php"><i class="icon-chevron-right"></i><i
                    class="icon-chevron-left"></i>&nbsp;Volver</a></li>
        <li class=""><a href="my_classmates.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-group"></i>&nbsp;Mi clase</a></li>
        <li class=""><a href="progress.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-bar-chart"></i>&nbsp;Mi progreso</a></li>
        <li class=""><a href="subject_overview_student.php<?php echo '?id=' . $get_id; ?>"><i
                    class="icon-chevron-right"></i><i class="icon-file"></i>&nbsp;Descripción
                de Asignatura</a></li>
        <li class="active"><a href="downloadable_student.php<?php echo '?id=' . $get_id; ?>"><i
                    class="icon-chevron-right"></i><i class="icon-download"></i>&nbsp;Tareas</a></li>
        <li class=""><a href="assignment_student.php<?php echo '?id=' . $get_id; ?>"><i
                    class="icon-chevron-right"></i><i class="icon-book"></i>&nbsp;Plan de estudios</a>
        </li>
        <li class=""><a href="announcements_student.php<?php echo '?id=' . $get_id; ?>"><i
                    class="icon-chevron-right"></i><i class="icon-info-sign"></i>&nbsp;Avisos</a>
        </li>
        <li class=""><a href="class_calendar_student.php<?php echo '?id=' . $get_id; ?>"><i
                    class="icon-chevron-right"></i><i class="icon-calendar"></i>&nbsp;Horario de
                Clases</a></li>
        <li class=""><a href="student_quiz_list.php<?php echo '?id=' . $get_id; ?>"><i class="icon-chevron-right"></i><i
                    class="icon-reorder"></i>&nbsp;Examenes</a>
        </li>
    </ul>
</div>