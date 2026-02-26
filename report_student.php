<?php
include('header_dashboard.php');
include('session.php');

$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$class_quiz_id = isset($_GET['class_quiz_id']) ? intval($_GET['class_quiz_id']) : 0;

if ($get_id <= 0 || $class_quiz_id <= 0) {
    error_log("Invalid parameters: get_id = " . $get_id . ", class_quiz_id = " . $class_quiz_id);
    die("Parámetros inválidos.");
}

$sql_quiz = mysqli_prepare($con, "SELECT quiz_title, quiz_description, grade, teacher_name 
    FROM punt_student_quiz 
    WHERE class_quiz_id = ?");

if ($sql_quiz) {
    mysqli_stmt_bind_param(
        $sql_quiz,
        "i",
        $class_quiz_id
    );

    mysqli_stmt_execute($sql_quiz);
    $result_quiz = mysqli_stmt_get_result($sql_quiz);

    if ($result_quiz) {
        $row_quiz = mysqli_fetch_array($result_quiz);

        if ($row_quiz) {
            $teacher_id = htmlspecialchars($row_quiz['teacher_name'], ENT_QUOTES, 'UTF-8');
            $quiz_title = htmlspecialchars($row_quiz['quiz_title'], ENT_QUOTES, 'UTF-8');
            $quiz_description = htmlspecialchars($row_quiz['quiz_description'], ENT_QUOTES, 'UTF-8');

            $sql_grade = mysqli_prepare($con, "SELECT grade FROM punt_student_quiz WHERE student_id = ? AND class_quiz_id = ?");
            if ($sql_grade) {
                mysqli_stmt_bind_param(
                    $sql_grade,
                    "ii",
                    $session_id,
                    $class_quiz_id
                );
                mysqli_stmt_execute($sql_grade);
                $result_grade = mysqli_stmt_get_result($sql_grade);
                if ($result_grade) {
                    $row_grade = mysqli_fetch_array($result_grade);
                    if ($row_grade) {
                        $grade = htmlspecialchars($row_grade['grade'], ENT_QUOTES, 'UTF-8');
                    } else {
                        $grade = "Sin calificar";
                    }
                } else {
                    error_log("MySQLi get_result error (grade): " . mysqli_error($con));
                    $grade = "Error al obtener la calificación.";
                }
                mysqli_stmt_close($sql_grade);
            } else {
                error_log("MySQLi prepare error (grade): " . mysqli_error($con));
                $grade = "Error al preparar la consulta de calificación.";
            }
        } else {
            error_log("Quiz not found for class_quiz_id = " . $class_quiz_id);
            die("Examen no encontrado.");
        }
    } else {
        error_log("MySQLi get_result error: " . mysqli_error($con));
        die("Error al obtener el resultado de la consulta del examen.");
    }

    mysqli_stmt_close($sql_quiz);
} else {
    error_log("MySQLi prepare error: " . mysqli_error($con));
    die("Error al preparar la consulta SQL del examen.");
}

// Nueva consulta para obtener el nombre del docente
$sql_teacher = mysqli_prepare($con, "SELECT firstname, lastname FROM teacher WHERE teacher_id = ?");
if ($sql_teacher) {
    mysqli_stmt_bind_param(
        $sql_teacher,
        "i",
        $teacher_id
    );
    mysqli_stmt_execute($sql_teacher);
    $result_teacher = mysqli_stmt_get_result($sql_teacher);
    if ($result_teacher) {
        $row_teacher = mysqli_fetch_array($result_teacher);
        if ($row_teacher) {
            $teacher_name = htmlspecialchars($row_teacher['firstname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row_teacher['lastname'], ENT_QUOTES, 'UTF-8');
        } else {
            $teacher_name = "Nombre del docente no encontrado";
        }
    } else {
        error_log("MySQLi get_result error (teacher): " . mysqli_error($con));
        $teacher_name = "Error al obtener el nombre del docente";
    }
    mysqli_stmt_close($sql_teacher);
} else {
    error_log("MySQLi prepare error (teacher): " . mysqli_error($con));
    $teacher_name = "Error al preparar la consulta del docente.";
}

?>

<!DOCTYPE html>
<html lang="sp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Examen</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
    }

    h1 {
        text-align: center;
    }

    .quiz-info {
        margin-bottom: 20px;
    }

    .result {
        font-size: 1.2em;
        font-weight: bold;
    }

    .print-button {
        text-align: center;
        margin-top: 20px;
    }

    @media print {
        .print-button {
            display: none;
        }
    }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
    window.jsPDF = window.jspdf.jsPDF;

    function generatePDF() {
        const element = document.getElementById('report-content');
        html2canvas(element).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF();
            const imgWidth = 210;
            const imgHeight = canvas.height * imgWidth / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.output('dataurlnewwindow');
            pdf.save('reporte_examen.pdf');
        });
    }
    </script>
</head>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container" id="report-content">
        <h1>Reporte de Examen</h1>
        <div class="quiz-info">
            <h2>
                <?php echo $quiz_title; ?>
            </h2>
            <p>
                <?php echo $quiz_description; ?>
            </p>
            <p>Profesor:
                <?php echo $teacher_name; ?>
            </p>
            <p>Escuela: Universidad Tecnologica de la Tarahumara
            </p>
        </div>
        <div class="result">
            Tu puntaje:
            <?php echo $grade; ?>
        </div>
    </div>
    <div class="print-button">
        <button class="btn btn-info" onclick="generatePDF()">Imprimir y Descargar Reporte</button>
        <a href="student_quiz_list.php?id=<?php echo htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8'); ?>"
            class="btn btn-primary">Volver a la lista de exámenes</a>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>