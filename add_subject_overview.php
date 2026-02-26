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
                        WHERE teacher_class_id = '$get_id'") or die(mysqli_error($con));
                    $class_row = mysqli_fetch_array($class_query);
                    if ($class_row) {
                        $class_name = htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8');
                        $subject_code = htmlspecialchars($class_row['subject_code'], ENT_QUOTES, 'UTF-8');
                    } else {
                        $class_name = "Unknown Class";
                        $subject_code = "Unknown Subject";
                    }
                    ?>
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo $class_name; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><?php echo $subject_code; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><b>Descripción de Asignatura</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-right">
                                <a href="subject_overview.php<?php echo '?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="btn btn-success"><i class="icon-arrow-left"></i>Volver</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword">Descripción de
                                            Asignatura:</label>
                                        <div class="controls">
                                            <textarea name="content" id="ckeditor_full"></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <button name="save" type="submit" class="btn btn-info"><i
                                                    class="icon-save"></i> Guardar</button>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['save'])) {
                                    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

                                    if (empty($content)) {
                                        echo "<div class='alert alert-danger'>Please enter the subject overview content.</div>";
                                    } else {
                                        $content = mysqli_real_escape_string($con, $content);
                                        $get_id = mysqli_real_escape_string($con, $get_id);

                                        $insert_query = mysqli_query($con, "INSERT INTO class_subject_overview (teacher_class_id, content) VALUES ('$get_id', '$content')") or die(mysqli_error($con));

                                        if ($insert_query) {
                                            header('Location: subject_overview.php?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8'));
                                            exit();
                                        } else {
                                            echo "<div class='alert alert-danger'>Error adding subject overview. Please try again.</div>";
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /block -->
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>