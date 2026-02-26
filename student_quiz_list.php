<?php
include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($get_id === false || $get_id === null) {
    die("Invalid class ID.");
}
?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('student_quiz_link.php'); ?>
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
                        $class_id = $class_row['class_id'];
                        $school_year = $class_row['school_year'];
                        if ($class_row) {
                    ?>
                    <ul class="breadcrumb">
                        <li><a
                                href="#"><?php echo htmlspecialchars((string)$class_row['class_name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a
                                href="#"><?php echo htmlspecialchars((string)$class_row['subject_code'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#">Año Escolar:
                                <?php echo htmlspecialchars((string)$class_row['school_year'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Examen</b></a></li>
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
                        <div class="navbar navbar-inner block-header">
                            <?php
                            $query = mysqli_prepare($con, "SELECT * FROM class_quiz 
                                LEFT JOIN quiz ON class_quiz.quiz_id = quiz.quiz_id
                                WHERE teacher_class_id = ?");

                            if ($query) {
                                mysqli_stmt_bind_param($query, "i", $get_id);
                                mysqli_stmt_execute($query);
                                $result = mysqli_stmt_get_result($query);
                                $count = mysqli_num_rows($result);
                            ?>
                            <div id="" class="muted pull-right"><span
                                    class="badge badge-info"><?php echo $count; ?></span></div>
                            <?php
                                mysqli_stmt_close($query);
                            } else {
                                die("Prepare failed: " . mysqli_error($con));
                            }
                            ?>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="take_test.php" method="post">

                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="">
                                        <thead>
                                            <tr>
                                                <th>Titulo de Examen</th>
                                                <th>Descripcion</th>
                                                <th>TIEMPO DE EXAMEN (EN MINUTOS)</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_prepare($con, "SELECT class_quiz.class_quiz_id, quiz.quiz_id, quiz.quiz_title, quiz.quiz_description, quiz.quiz_time
                                                    FROM class_quiz 
                                                    LEFT JOIN quiz ON class_quiz.quiz_id = quiz.quiz_id
                                                    WHERE teacher_class_id = ?  
                                                    GROUP BY class_quiz.class_quiz_id
                                                    ORDER BY class_quiz.class_quiz_id DESC");

                                            if ($query) {
                                                mysqli_stmt_bind_param($query, "i", $get_id);
                                                mysqli_stmt_execute($query);
                                                $result = mysqli_stmt_get_result($query);

                                                while ($row = mysqli_fetch_array($result)) {
                                                    $id = htmlspecialchars((string)$row['class_quiz_id'], ENT_QUOTES, 'UTF-8');
                                                    $quiz_id = htmlspecialchars((string)$row['quiz_id'], ENT_QUOTES, 'UTF-8');
                                                    $quiz_time = $row['quiz_time']; 

                                                    $query1 = mysqli_prepare($con, "SELECT * FROM student_class_quiz WHERE class_quiz_id = ? AND student_id = ?");
                                                    if ($query1) {
                                                        mysqli_stmt_bind_param($query1, "ii", $id, $session_id);
                                                        mysqli_stmt_execute($query1);
                                                        $result1 = mysqli_stmt_get_result($query1);
                                                        $row1 = mysqli_fetch_array($result1);
                                                        if ($row1) {
                                                            $grade = htmlspecialchars((string)$row1['grade'], ENT_QUOTES, 'UTF-8');
                                                        } else {
                                                            $grade = "";
                                                        }

                                                        mysqli_stmt_close($query1);
                                                    } else {
                                                        die("Prepare failed: " . mysqli_error($con));
                                                    }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars((string)$row['quiz_title'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars((string)$row['quiz_description'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo (int)$quiz_time / 60; ?>
                                                </td>
                                                <td width="200">
                                                    <?php if ($grade == "") { ?>
                                                    <a data-placement="bottom" title="Realizar examen"
                                                        data-toggle="tooltip"
                                                        href="take_test.php<?php echo '?id=' . urlencode($get_id) . '&class_quiz_id=' . urlencode($id) . '&test=ok&quiz_id=' . urlencode($quiz_id); ?>">
                                                        <i class="icon-check icon-large"></i> Realizar examen
                                                    </a>
                                                    <?php } else { ?>
                                                    <b>Already Taken Score <?php echo $grade; ?></b>
                                                    <?php } ?>
                                                </td>
                                                <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $('#<?php echo $id; ?>Take This Quiz').tooltip('show');
                                                    $('#<?php echo $id; ?>Take This Quiz').tooltip('hide');
                                                });
                                                </script>
                                            </tr>
                                            <?php
                                                }
                                                mysqli_stmt_close($query);
                                            } else {
                                                die("Prepare failed: " . mysqli_error($con));
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </form>
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