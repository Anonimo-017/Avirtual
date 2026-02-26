<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($get_id === false || $get_id === null) {
    die("Error Invalid class ID.");
}
?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('calendar_student_sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    $class_query = mysqli_prepare($con, "SELECT * FROM teacher_class
                        LEFT JOIN class ON class.class_id = teacher_class.class_id
                        LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
                        WHERE teacher_class_id = ?");

                    if ($class_query) {
                        mysqli_stmt_bind_param($class_query, "i", $get_id); 
                        mysqli_stmt_execute($class_query);
                        $class_result = mysqli_stmt_get_result($class_query);
                        $class_row = mysqli_fetch_array($class_result);

                        if ($class_row) {
                            ?>
                    <ul class="breadcrumb">
                        <li><a
                                href="#"><?php echo htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a
                                href="#"><?php echo htmlspecialchars($class_row['subject_code'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#">Año Escolar:
                                <?php echo htmlspecialchars($class_row['school_year'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Mi Calendario de Clases</b></a></li>
                    </ul>
                    <?php
                        } else {
                            echo "<div class='alert alert-danger'>Class not found.</div>";
                        }

                        mysqli_stmt_close($class_query);
                    } else {
                        die("Prepare failed: " . mysqli_error($con));
                    }
                    ?>
                    <div id="block_bg" class="block">

                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="navbar navbar-inner block-header">
                                    <div class="muted pull-left">Calendar</div>
                                </div>
                                <div id='calendar'></div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
    <?php include('class_calendar_script.php'); ?>
</body>

</html>