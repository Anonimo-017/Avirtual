<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php include('admin/dbcon.php'); ?>
<?php
$get_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!is_numeric($get_id)) {
    die("Invalid class ID.");
}
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('subject_overview_link.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    $class_query = mysqli_query($con, "SELECT class.class_name, subject.subject_code FROM teacher_class
                        LEFT JOIN class ON class.class_id = teacher_class.class_id
                        LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
                        WHERE teacher_class_id = '$get_id' LIMIT 1") or die(mysqli_error($con));
                    $class_row = mysqli_fetch_array($class_query);

                    $class_name = $class_row ? htmlspecialchars((string)$class_row['class_name'], ENT_QUOTES, 'UTF-8') : "Unknown Class";
                    $subject_code = $class_row ? htmlspecialchars((string)$class_row['subject_code'], ENT_QUOTES, 'UTF-8') : "Unknown Subject";
                    ?>

                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo $class_name; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><?php echo $subject_code; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><b>Descripción de Asignatura</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-right">
                                <?php
                                $overview_query = mysqli_query($con, "SELECT class_subject_overview_id FROM class_subject_overview WHERE teacher_class_id = '$get_id' LIMIT 1") or die(mysqli_error($con));
                                $overview_row = mysqli_fetch_array($overview_query);
                                $overview_id = $overview_row ? $overview_row['class_subject_overview_id'] : null;
                                $overview_count = mysqli_num_rows($overview_query);

                                if ($overview_count > 0) {
                                    echo "<a href='edit_subject_overview.php?id=" . htmlspecialchars((string)$get_id, ENT_QUOTES, 'UTF-8') . "&subject_id=" . htmlspecialchars((string)$overview_id, ENT_QUOTES, 'UTF-8') . "' class='btn btn-info'><i class='icon-pencil'></i> Editar Descripción de Asignatura</a>";
                                } else {
                                    echo "<a href='add_subject_overview.php?id=" . htmlspecialchars((string)$get_id, ENT_QUOTES, 'UTF-8') . "' class='btn btn-success'><i class='icon-plus-sign'></i> Agregar Descripción de Asignatura</a>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php
                                $content_query = mysqli_query($con, "SELECT content FROM class_subject_overview WHERE teacher_class_id = '$get_id' LIMIT 1") or die(mysqli_error($con));
                                $content_row = mysqli_fetch_array($content_query);

                                if ($content_row) {
                                    echo $content_row['content']; // o usar htmlspecialchars() si es texto plano
                                } else {
                                    echo "No subject overview available.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <?php include('script.php'); ?>
</body>

</html>