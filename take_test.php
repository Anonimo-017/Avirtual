<?php
include('header_dashboard.php');
include('session.php');

$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$class_quiz_id = isset($_GET['class_quiz_id']) ? intval($_GET['class_quiz_id']) : 0;
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if ($get_id <= 0 || $class_quiz_id <= 0 || $quiz_id <= 0) {
	die("Invalid parameters.");
}

$show_quiz = isset($_GET['test']) && $_GET['test'] == 'ok';
$show_results = false; 

$query1 = mysqli_prepare($con, "SELECT * FROM punt_student_quiz WHERE class_quiz_id = ? AND student_id = ?");
if ($query1) {
	mysqli_stmt_bind_param($query1, "ii", $class_quiz_id, $session_id);
	mysqli_stmt_execute($query1);
	$result1 = mysqli_stmt_get_result($query1);
	$row1 = mysqli_fetch_array($result1);
	if ($row1) {
?>
<script>
window.location =
    'report_student.php<?php echo '?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8') . '&class_quiz_id=' . htmlspecialchars($class_quiz_id, ENT_QUOTES, 'UTF-8'); ?>';
</script>
<?php
	} else {

		if (isset($_POST['submit_answer'])) {
			$show_results = true;
			$x1 = isset($_POST['x']) ? intval($_POST['x']) : 0;
			$score = 0;

			for ($x = 1; $x <= $x1; $x++) {
				$x2 = isset($_POST["x-$x"]) ? intval($_POST["x-$x"]) : 0;
				$q = isset($_POST["q-$x2"]) ? $_POST["q-$x2"] : '';

				$sql = mysqli_prepare($con, "SELECT answer FROM quiz_question WHERE quiz_question_id = ?");
				if ($sql) {
					mysqli_stmt_bind_param($sql, "i", $x2);
					mysqli_stmt_execute($sql);
					$result = mysqli_stmt_get_result($sql);
					$row = mysqli_fetch_array($result);

					if ($row && $row['answer'] == $q) {
						$score++;
					}
					mysqli_stmt_close($sql);
				} else {
					error_log("MySQLi prepare error: " . mysqli_error($con));
					die("Failed to prepare SQL query");
				}
			}

			$grade = $score . " out of " . ($x1);

			$school_name = "Nombre de la Escuela"; 
			$teacher_name_query = mysqli_prepare($con, "SELECT t.firstname, t.lastname FROM teacher t INNER JOIN teacher_class tc ON t.teacher_id = tc.teacher_class_id WHERE tc.teacher_class_id = ?");
			if ($teacher_name_query) {
				mysqli_stmt_bind_param($teacher_name_query, "i", $get_id);
				mysqli_stmt_execute($teacher_name_query);
				$teacher_name_result = mysqli_stmt_get_result($teacher_name_query);
				$teacher_name_row = mysqli_fetch_array($teacher_name_result);
				if ($teacher_name_row) {
					$teacher_name = htmlspecialchars($teacher_name_row['firstname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($teacher_name_row['lastname'], ENT_QUOTES, 'UTF-8');
				} else {
					$teacher_name = "Nombre del Docente no encontrado";
				}
				mysqli_stmt_close($teacher_name_query);
			} else {
				die("Prepare failed: " . mysqli_error($con));
			}

			$quiz_info_query = mysqli_prepare($con, "SELECT quiz_title, quiz_description FROM quiz WHERE quiz_id = ?");
			if ($quiz_info_query) {
				mysqli_stmt_bind_param($quiz_info_query, "i", $quiz_id);
				mysqli_stmt_execute($quiz_info_query);
				$quiz_info_result = mysqli_stmt_get_result($quiz_info_query);
				$quiz_info_row = mysqli_fetch_array($quiz_info_result);
				if ($quiz_info_row) {
					$quiz_title = htmlspecialchars($quiz_info_row['quiz_title'], ENT_QUOTES, 'UTF-8');
					$quiz_description = htmlspecialchars($quiz_info_row['quiz_description'], ENT_QUOTES, 'UTF-8');
				} else {
					$quiz_title = "Título del Examen no encontrado";
					$quiz_description = "Descripción del Examen no encontrada";
				}
				mysqli_stmt_close($quiz_info_query);
			} else {
				die("Prepare failed: " . mysqli_error($con));
			}

			$insert_query = mysqli_prepare($con, "INSERT INTO punt_student_quiz (student_id, class_quiz_id, grade, school_name, teacher_name, quiz_title, quiz_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
			if ($insert_query) {
				mysqli_stmt_bind_param($insert_query, "iisssss", $session_id, $class_quiz_id, $grade, $school_name, $teacher_name, $quiz_title, $quiz_description);
				mysqli_stmt_execute($insert_query);
				mysqli_stmt_close($insert_query);
			} else {
				error_log("MySQLi prepare error: " . mysqli_error($con));
				die("Failed to prepare SQL query");
			}
			$report_url = 'report_student.php?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8') . '&class_quiz_id=' . htmlspecialchars($class_quiz_id, ENT_QUOTES, 'UTF-8');
			header("Location: " . $report_url);
			exit;
		} elseif ($show_quiz) {
		?>
<!DOCTYPE html>
<html lang="sp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen</title>
    <?php include('header_dashboard.php'); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('student_quiz_link.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
								$class_query = mysqli_prepare($con, "SELECT c.class_name, s.subject_code, tc.school_year
                            FROM teacher_class tc
                            LEFT JOIN class c ON c.class_id = tc.class_id
                            LEFT JOIN subject s ON s.subject_id = tc.subject_id
                            WHERE tc.teacher_class_id = ?");
								if ($class_query) {
									mysqli_stmt_bind_param($class_query, "i", $get_id);
									mysqli_stmt_execute($class_query);
									$class_result = mysqli_stmt_get_result($class_query);
									$class_row = mysqli_fetch_array($class_result);
									if ($class_row) {
										$class_name = htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8');
										$subject_code = htmlspecialchars($class_row['subject_code'], ENT_QUOTES, 'UTF-8');
										$school_year = htmlspecialchars($class_row['school_year'], ENT_QUOTES, 'UTF-8');
								?>
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo $class_name; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><?php echo $subject_code; ?></a> <span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo $school_year; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><b>Examen</b></a></li>
                    </ul>
                    <?php
									} else {
										echo "<div class='alert alert-danger'>Class not found.</div>";
									}
									mysqli_stmt_close($class_query);
								} else {
									error_log("MySQLi prepare error: " . mysqli_error($con));
									die("Failed to prepare SQL query");
								}
								?>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <?php
										$sqlp = mysqli_prepare($con, "SELECT quiz.quiz_title, quiz.quiz_description, class_quiz.quiz_id
                                    FROM class_quiz
                                    LEFT JOIN quiz ON quiz.quiz_id = class_quiz.quiz_id
                                    WHERE class_quiz.class_quiz_id = ?");
										if ($sqlp) {
											mysqli_stmt_bind_param($sqlp, "i", $class_quiz_id);
											mysqli_stmt_execute($sqlp);
											$resultp = mysqli_stmt_get_result($sqlp);
											$rowp = mysqli_fetch_array($resultp);

											if ($rowp) {
												$quiz_title = htmlspecialchars($rowp['quiz_title'], ENT_QUOTES, 'UTF-8');
												$quiz_description = htmlspecialchars($rowp['quiz_description'], ENT_QUOTES, 'UTF-8');
												$quiz_id_from_class_quiz = $rowp['quiz_id'];

												echo '<form action="take_test.php?id=' . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8') . '&class_quiz_id=' . htmlspecialchars($class_quiz_id, ENT_QUOTES, 'UTF-8') . '&quiz_id=' . htmlspecialchars($quiz_id, ENT_QUOTES, 'UTF-8') . '" name="testform" method="POST" id="test-form">';
												echo '<h3>Titulo del examen: <b>' . $quiz_title . '</b></h3>';
												echo '<p><b>Descripcion: ' . $quiz_description . '</b></p>';
												echo '<p></p>';

												echo '<table class="questions-table table">';
												echo '<tr><th>#</th><th>Preguntas</th></tr>';

												$sqlw = mysqli_prepare($con, "SELECT quiz_question_id, question_text, question_type_id FROM quiz_question WHERE quiz_id = ? ORDER BY RAND()");
												if ($sqlw) {
													mysqli_stmt_bind_param($sqlw, "i", $quiz_id_from_class_quiz);
													mysqli_stmt_execute($sqlw);
													$resultw = mysqli_stmt_get_result($sqlw);
													$x = 0;
													while ($roww = mysqli_fetch_array($resultw)) {
														$x++;
														$quiz_question_id = htmlspecialchars($roww['quiz_question_id'], ENT_QUOTES, 'UTF-8');
														$question_text = $roww['question_text'];
														$question_type_id = htmlspecialchars($roww['question_type_id'], ENT_QUOTES, 'UTF-8');

														echo '<tr id="q_' . $x . '" class="questions">';
														echo '<td width="30" id="qa">' . $x . '</td>';
														echo '<td id="qa">' . $question_text . '<br><hr>';

														if ($question_type_id == '2') {
															echo '<input name="q-' . $quiz_question_id . '" value="True" type="radio"> True&nbsp;|&nbsp;<input name="q-' . $quiz_question_id . '" value="False" type="radio"> False';
														} else if ($question_type_id == '1') {
															$sqly = mysqli_prepare($con, "SELECT answer_text, choices FROM answer WHERE quiz_question_id = ?");
															if ($sqly) {
																mysqli_stmt_bind_param($sqly, "i", $quiz_question_id);
																mysqli_stmt_execute($sqly);
																$resulty = mysqli_stmt_get_result($sqly);
																while ($rowy = mysqli_fetch_array($resulty)) {
																	$answer_text = htmlspecialchars($rowy['answer_text'], ENT_QUOTES, 'UTF-8');
																	$choices = htmlspecialchars($rowy['choices'], ENT_QUOTES, 'UTF-8');

																	echo $choices . '.) <input name="q-' . $quiz_question_id . '" value="' . $choices . '" type="radio"> ' . $answer_text . '<br><br>';
																}
																mysqli_stmt_close($sqly);
															} else {
																error_log("MySQLi prepare error: " . mysqli_error($con));
																die("Failed to prepare SQL query");
															}
														}

														echo '<input type="hidden" name="x-' . $x . '" value="' . $quiz_question_id . '">';
														echo '</td></tr>';
													}
													mysqli_stmt_close($sqlw);
												} else {
													error_log("MySQLi prepare error: " . mysqli_error($con));
													die("Failed to prepare SQL query");
												}

												echo '<tr><td></td><td><button class="btn btn-info" id="submit-test" name="submit_answer"><i class="icon-check"></i>Responder pregunta</button></td></tr>';
												echo '</table><input type="hidden" name="x" value="' . $x . '"></form>';
											} else {
												echo "<div class='alert alert-danger'>Pregunta no encontrada.</div>";
											}
											mysqli_stmt_close($sqlp);
										} else {
											error_log("MySQLi prepare error: " . mysqli_error($con));
											die("Failed to prepare SQL query");
										}
										?>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include('script.php'); ?>
</body>

</html>
<?php
		} else {
			header("Location: student_quiz_list.php?id=" . htmlspecialchars($get_id, ENT_QUOTES, 'UTF-8'));
			exit;
		}
	}
	mysqli_stmt_close($query1);
} else {
	error_log("MySQLi prepare error: " . mysqli_error($con));
	die("Failed to prepare SQL query");
}
?>