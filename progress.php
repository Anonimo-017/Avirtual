<?php
include('header_dashboard.php');
include('session.php');

// 1. Sanear y validar $_GET['id']
$get_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($get_id <= 0) {
    $_SESSION['error_message'] = "ID de clase inválido.";
    header("Location: dashboard_student.php"); // Redirigir a una página de error
    exit;
}

// 2. Verificar que la clase pertenece al estudiante
try {
    $stmt_check = $pdo_conn->prepare("
        SELECT 1
        FROM teacher_class_student tcs
        WHERE tcs.student_id = :student_id AND tcs.teacher_class_id = :teacher_class_id
    ");
    $stmt_check->execute(['student_id' => (int)$session_id, 'teacher_class_id' => $get_id]);
    if ($stmt_check->rowCount() == 0) {
        $_SESSION['error_message'] = "No estás autorizado a ver esta clase.";
        header("Location: dashboard_student.php"); // Redirigir a una página de error
        exit;
    }
} catch (PDOException $e) {
    error_log("Error al verificar la clase: " . $e->getMessage());
    $_SESSION['error_message'] = "Error al verificar la clase. Inténtalo de nuevo más tarde.";
    header("Location: dashboard_student.php"); // Redirigir a una página de error
    exit;
}

// Función para obtener la información de la clase
function getClassInfo($pdo_conn, $get_id)
{
    $stmt_class = $pdo_conn->prepare("
        SELECT tc.class_id, tc.school_year, c.class_name, s.subject_code
        FROM teacher_class tc
        LEFT JOIN class c ON c.class_id = tc.class_id
        LEFT JOIN subject s ON s.subject_id = tc.subject_id
        WHERE tc.teacher_class_id = :get_id
        LIMIT 1
    ");
    $stmt_class->execute(['get_id' => (int)$get_id]);
    $class_row = $stmt_class->fetch(PDO::FETCH_ASSOC);
    return $class_row;
}

$class_row = getClassInfo($pdo_conn, $get_id);

if (!$class_row) {
    $_SESSION['error_message'] = "Clase no encontrada.";
    header("Location: dashboard_student.php"); // Redirigir a una página de error
    exit;
}

$class_id = $class_row['class_id'] ?? 0;
$school_year = $class_row['school_year'] ?? '';
?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">

            <?php include('progress_link_student.php'); ?>

            <!-- Assignment Grade Progress -->
            <div class="span4" id="content">
                <div class="row-fluid">
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo htmlspecialchars($class_row['class_name'] ?? ''); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#"><?php echo htmlspecialchars($class_row['subject_code'] ?? ''); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo htmlspecialchars($school_year); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#"><b></b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">
                                <h4>Assignment Grade Progress</h4>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <table class="table" cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                        <tr>
                                            <th>Fecha Subida</th>
                                            <th>Assignment</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            $stmt_assign = $pdo_conn->prepare("
        SELECT sa.assignment_fdatein, sa.grade, sa.fname AS assignment_name
        FROM student_assignment sa
        WHERE sa.student_id = :student_id
        ORDER BY sa.assignment_fdatein DESC
    ");
                                            $stmt_assign->execute(['student_id' => (int)$session_id]);

                                            while ($row = $stmt_assign->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['assignment_fdatein']); ?></td>
                                            <td><?php echo htmlspecialchars($row['assignment_name']); ?></td>
                                            <td><span
                                                    class="badge badge-success"><?php echo htmlspecialchars($row['grade']); ?></span>
                                            </td>
                                        </tr>
                                        <?php endwhile;
                                        } catch (PDOException $e) {
                                            error_log("Error al obtener las asignaciones: " . $e->getMessage());
                                            echo '<div class="alert alert-danger">Error al obtener las asignaciones: ' . htmlspecialchars($e->getMessage()) . '</div>'; // Mostrar el mensaje completo
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="span5" id="content">
                <div class="row-fluid">
                    <ul class="breadcrumb">
                        <li><a href="#"><b>..</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">
                                <h4>Practice Quiz Progress</h4>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <table class="table" cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                        <tr>
                                            <th>Titulo de Examen</th>
                                            <th>Descripcion</th>
                                            <th>Tiempo de Examen (minutos)</th>
                                            <th>Nota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            // Traer quizzes de la clase
                                            $stmt_quiz = $pdo_conn->prepare("
                                                SELECT cq.class_quiz_id, q.quiz_title, q.quiz_description, q.quiz_time
                                                FROM class_quiz cq
                                                LEFT JOIN quiz q ON cq.quiz_id = q.quiz_id
                                                WHERE cq.teacher_class_id = :teacher_class_id
                                                ORDER BY cq.class_quiz_id DESC
                                            ");
                                            $stmt_quiz->execute(['teacher_class_id' => (int)$get_id]);

                                            while ($quiz = $stmt_quiz->fetch(PDO::FETCH_ASSOC)):
                                                $class_quiz_id = $quiz['class_quiz_id'];

                                                // Traer nota del estudiante si ya hizo el quiz
                                                $stmt_grade = $pdo_conn->prepare("
                                                    SELECT grade
                                                    FROM student_class_quiz
                                                    WHERE class_quiz_id = :class_quiz_id AND student_id = :student_id
                                                    LIMIT 1
                                                ");
                                                $stmt_grade->execute([
                                                    'class_quiz_id' => $class_quiz_id,
                                                    'student_id' => (int)$session_id
                                                ]);
                                                $grade_row = $stmt_grade->fetch(PDO::FETCH_ASSOC);
                                                $grade = $grade_row ? $grade_row['grade'] : 'N/A';

                                                // Tiempo de examen
                                                $quiz_time = isset($quiz['quiz_time']) ? $quiz['quiz_time'] : 'N/A';
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($quiz['quiz_title']); ?></td>
                                            <td><?php echo htmlspecialchars($quiz['quiz_description']); ?></td>
                                            <td><?php echo htmlspecialchars($quiz_time); ?></td>
                                            <td><?php echo htmlspecialchars($grade); ?></td>
                                        </tr>
                                        <?php endwhile;
                                        } catch (PDOException $e) {
                                            error_log("Error al obtener los quizzes: " . $e->getMessage());
                                            echo '<div class="alert alert-danger">Error al obtener los quizzes. Inténtalo de nuevo más tarde.</div>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
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