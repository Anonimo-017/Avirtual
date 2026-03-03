<div class="span3" id="sidebar">
    <?php
    $session_id = mysqli_real_escape_string($con, $session_id);
    $query = mysqli_query($con, "SELECT * FROM teacher WHERE teacher_id = '$session_id'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);

    if ($row) {
        $location = htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8');

        echo '<img id="avatar" class="img-polaroid" src="' . $location . '">';
    } else {
        echo '<img id="avatar" class="img-polaroid" src="admin/uploads">';
        echo "<p>Error: Teacher information not available.</p>";
    }
    ?>

    <?php include('teacher_count.php'); ?>

    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class=""><a href="dashboard_teacher.php"><i class="icon-chevron-right"></i><i
                    class="icon-group"></i>&nbsp;Mi Clase</a></li>
        <li class=""><a href="teacher_quiz.php"><i class="icon-chevron-right"></i><i class="icon-list"></i>&nbsp;Agregar
                examen</a></li>
        <li class="active"><a href="punt_alum.php"><i class="icon-chevron-right"></i><i
                    class="icon-list"></i>&nbsp;Calificaciones</a></li>
    </ul>
</div>