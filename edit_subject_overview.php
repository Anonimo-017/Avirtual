<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php include('admin/dbcon.php');  ?>
<?php
$get_id = isset($_GET['id']) ? $_GET['id'] : null;
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;
if (!is_numeric($get_id) || !is_numeric($subject_id)) {
    die("Invalid class or subject ID.");
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
                        $class_name = htmlspecialchars((string)$class_row['class_name'], ENT_QUOTES, 'UTF-8');
                        $subject_code = htmlspecialchars((string)$class_row['subject_code'], ENT_QUOTES, 'UTF-8');
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
                                    class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php
                                $subject_query = mysqli_query($con, "SELECT content FROM class_subject_overview WHERE class_subject_overview_id = '$subject_id'") or die(mysqli_error($con));
                                $subject_row = mysqli_fetch_array($subject_query);

                                if ($subject_row) {
                                    $content = $subject_row['content'];
                                } else {
                                    $content = "";
                                }
                                ?>
                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword">Descripción de Asignatura
                                            Content:</label>
                                        <div class="controls">
                                            <textarea name="content"
                                                id="ckeditor_full"><?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <button name="save" type="submit" class="btn btn-success"><i
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
                                        $subject_id = mysqli_real_escape_string($con, $subject_id);

                                        $update_query = mysqli_query($con, "UPDATE class_subject_overview SET content = '$content' WHERE class_subject_overview_id = '$subject_id'") or die(mysqli_error($con));

                                        if ($update_query) {
                                            header('Location: subject_overview.php?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8'));
                                            exit();
                                        } else {
                                            echo "<div class='alert alert-danger'>Error updating subject overview. Please try again.</div>";
                                        }
                                    }
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