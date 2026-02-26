<div class="span3" id="sidebar">
    <img id="avatar" class="img-polaroid" src="<?php echo $row['location']; ?>">
    <?php include('teacher_count.php'); ?>
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class=""><a href="dashboard_teacher.php"><i class="icon-chevron-right"></i><i
                    class="icon-group"></i>&nbsp;Mi Clase</a></li>
        <li class="active"><a href="teacher_quiz.php"><i class="icon-chevron-right"></i><i
                    class="icon-list"></i>&nbsp;Agregar
                examen</a></li>

    </ul>
</div>