<?php
include('header_dashboard.php');
include('session.php');
include('dbcon.php');

$get_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($get_id <= 0) {
    $_SESSION['error_message'] = "ID de clase inválido.";
    header("Location: dashboard_student.php");
    exit;
}

try {
    $stmt_check = $pdo_conn->prepare("
        SELECT 1
        FROM teacher_class_student tcs
        INNER JOIN teacher_class tc ON tc.teacher_class_id = tcs.teacher_class_id
        WHERE tcs.student_id = :student_id AND tc.teacher_class_id = :teacher_class_id
    ");
    $stmt_check->execute(['student_id' => (int)$session_id, 'teacher_class_id' => $get_id]);
    if ($stmt_check->rowCount() == 0) {
        $_SESSION['error_message'] = "No estás autorizado a ver esta clase.";
        header("Location: dashboard_student.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Error al verificar la clase: " . $e->getMessage());
    $_SESSION['error_message'] = "Error al verificar la clase. Inténtalo de nuevo más tarde.";
    header("Location: dashboard_student.php");
    exit;
}
?>

<body>
    <?php include('navbar_student.php'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('my_classmates_link.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    try {
                        $stmt = $pdo_conn->prepare("
                            SELECT 
                                tc.teacher_class_id,
                                c.class_name,
                                s.subject_code,
                                tc.school_year
                            FROM teacher_class_student tcs
                            INNER JOIN teacher_class tc ON tc.teacher_class_id = tcs.teacher_class_id
                            INNER JOIN class c ON c.class_id = tc.class_id
                            INNER JOIN subject s ON s.subject_id = tc.subject_id
                            WHERE tcs.student_id = :student_id AND tc.teacher_class_id = :teacher_class_id
                            LIMIT 1
                        ");
                        $stmt->execute(['student_id' => (int)$session_id, 'teacher_class_id' => $get_id]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$row) {
                            $_SESSION['error_message'] = "Clase no encontrada.";
                            header("Location: dashboard_student.php");
                            exit;
                        }
                    } catch (PDOException $e) {
                        error_log("Error al obtener la información de la clase: " . $e->getMessage());
                        $_SESSION['error_message'] = "Error al obtener la información de la clase. Inténtalo de nuevo más tarde.";
                        header("Location: dashboard_student.php");
                        exit;
                    }
                    ?>

                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo htmlspecialchars($row['class_name']); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#"><?php echo htmlspecialchars($row['subject_code']); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo htmlspecialchars($row['school_year']); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Mi grupo</b></a></li>
                    </ul>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <ul id="da-thumbs" class="da-thumbs">
                                    <?php
                                    try {
                                        $stmt_students = $pdo_conn->prepare("
                                            SELECT s.student_id, s.firstname, s.lastname, s.location
                                            FROM teacher_class_student tcs
                                            INNER JOIN student s ON s.student_id = tcs.student_id
                                            WHERE tcs.teacher_class_id = :teacher_class_id
                                            ORDER BY s.lastname
                                        ");
                                        $stmt_students->execute(['teacher_class_id' => $get_id]);

                                        while ($student = $stmt_students->fetch(PDO::FETCH_ASSOC)) {
                                            $student_id = $student['student_id'];
                                            $avatar = !empty($student['location']) ? $student['location'] : '';
                                    ?>
                                    <li id="del<?php echo htmlspecialchars($student_id); ?>">
                                        <a class="classmate_cursor" href="#">
                                            <img id="student_avatar_class"
                                                src="<?php echo htmlspecialchars($avatar); ?>" width="124" height="140"
                                                class="img-polaroid">
                                            <div><span></span></div>
                                        </a>
                                        <p class="class"><?php echo htmlspecialchars($student['lastname']); ?></p>
                                        <p class="subject"><?php echo htmlspecialchars($student['firstname']); ?></p>
                                    </li>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        error_log("Error al obtener los compañeros de clase: " . $e->getMessage());
                                        echo '<div class="alert alert-danger">Error al obtener los compañeros de clase. Inténtalo de nuevo más tarde.</div>';
                                    }
                                    ?>
                                </ul>
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