<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php include('admin/dbcon.php');  ?>
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
            <?php include('class_sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">

                    <div class="pull-right">
                        <a href="my_students.php<?php echo '?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8'); ?>"
                            class="btn btn-info"><i class="icon-arrow-left"></i> Back</a>
                    </div>
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
                        <li><a href="#">Mis Estudiantes</a><span class="divider">/</span></li>
                        <li><a href="#"><b>Agregar Estudiante</b></a></li>
                    </ul>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form method="post" action="">
                                    <button name="submit" type="submit" class="btn btn-info"><i
                                            class="icon-save"></i>&nbsp;Agregar Estudiante</button>
                                    <br><br>

                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <thead>
                                            <tr>
                                                <th>Foto</th>
                                                <th>Nombre</th>
                                                <th>Año de Curso y Sección</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $student_query = mysqli_query($con, "SELECT * FROM student LEFT JOIN class ON class.class_id = student.class_id ORDER BY lastname") or die(mysqli_error($con));
                                            $student_count = 0;
                                            while ($student_row = mysqli_fetch_array($student_query)) {
                                                $student_count++;
                                                $student_id = $student_row['student_id'];
                                                $firstname = htmlspecialchars($student_row['firstname'], ENT_QUOTES, 'UTF-8');
                                                $lastname = htmlspecialchars($student_row['lastname'], ENT_QUOTES, 'UTF-8');
                                                $class_name = htmlspecialchars((string)$student_row['class_name'], ENT_QUOTES, 'UTF-8');
                                                $location = htmlspecialchars($student_row['location'], ENT_QUOTES, 'UTF-8');
                                                echo "<tr>";
                                                echo "<input type='hidden' name='student_count' value='$student_count'>";
                                                echo "<td width='70'><img class='img-rounded' src='$location' height='50' width='40'></td>";
                                                echo "<td>$firstname $lastname</td>";
                                                echo "<td>$class_name</td>";
                                                echo "<td width='80'>";
                                                echo "<select name='add_student[$student_id]' class='span12'>";
                                                echo "<option value=''></option>";
                                                echo "<option value='add'>Agregar</option>";
                                                echo "</select>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </form>

                                <?php
                                if (isset($_POST['submit'])) {
                                    $student_count = $_POST['student_count'];
                                    $added_count = 0;

                                    foreach ($_POST['add_student'] as $student_id => $action) {
                                        if ($action === 'add') {
                                            $student_id = mysqli_real_escape_string($con, $student_id);
                                            $class_id = mysqli_real_escape_string($con, $get_id);
                                            $teacher_id = mysqli_real_escape_string($con, $session_id);

                                            $check_query = mysqli_query($con, "SELECT * FROM teacher_class_student WHERE student_id = '$student_id' AND teacher_class_id = '$class_id'") or die(mysqli_error($con));
                                            $check_count = mysqli_num_rows($check_query);

                                            if ($check_count > 0) {
                                                echo "<script>alert('Student with ID $student_id is already in the class.');</script>";
                                            } else {
                                                $insert_query = mysqli_query($con, "INSERT INTO teacher_class_student (student_id, teacher_class_id, teacher_id) VALUES ('$student_id', '$class_id', '$teacher_id')") or die(mysqli_error($con));
                                                if ($insert_query) {
                                                    $added_count++;
                                                }
                                            }
                                        }
                                    }
                                    echo "<script>window.location = 'my_students.php?id=" . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8') . "';</script>";
                                    exit();
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