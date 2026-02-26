<?php
include('header_dashboard.php');
include('session.php');
include('dbcon.php');

$get_id = $_GET['id'];
$quiz_question_id = $_GET['quiz_question_id'];
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('quiz_sidebar_teacher.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">

                    <!-- breadcrumb -->
                    <ul class="breadcrumb">
                        <?php
						$stmt = $pdo_conn->query("SELECT * FROM school_year ORDER BY school_year DESC");
						$school_year_query_row = $stmt->fetch();
						?>
                        <li><a href="#"><b>Mi Clase</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo $school_year_query_row['school_year']; ?></a><span
                                class="divider">/</span></li>
                        <li><a href="#"><b>Quiz Question</b></a></li>
                    </ul>
                    <!-- end breadcrumb -->

                    <!-- block -->
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-right">
                                <a href="quiz_question.php?id=<?php echo $get_id; ?>" class="btn btn-success"><i
                                        class="icon-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">

                                <?php
								// Obtener la pregunta
								$stmt = $pdo_conn->prepare("
                                    SELECT * FROM quiz_question 
                                    LEFT JOIN question_type ON quiz_question.question_type_id = question_type.question_type_id
                                    WHERE quiz_question_id = :quiz_question_id
                                ");
								$stmt->execute([':quiz_question_id' => $quiz_question_id]);
								$row = $stmt->fetch();

								// Obtener respuestas
								$stmt2 = $pdo_conn->prepare("SELECT * FROM answer WHERE quiz_question_id = :quiz_question_id");
								$stmt2->execute([':quiz_question_id' => $quiz_question_id]);
								$answers = $stmt2->fetchAll(PDO::FETCH_ASSOC);

								$a = $b = $c = $d = '';
								foreach ($answers as $ans) {
									if ($ans['choices'] == 'A') $a = $ans['answer_text'];
									if ($ans['choices'] == 'B') $b = $ans['answer_text'];
									if ($ans['choices'] == 'C') $c = $ans['answer_text'];
									if ($ans['choices'] == 'D') $d = $ans['answer_text'];
								}
								?>

                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label">Pregunta</label>
                                        <div class="controls">
                                            <!-- CKEditor -->
                                            <textarea name="question" id="ckeditor_full"
                                                required><?php echo html_entity_decode($row['question_text']); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Tipo de pregunta:</label>
                                        <div class="controls">
                                            <select id="qtype" name="question_type" required>
                                                <option value="<?php echo $row['question_type_id']; ?>">
                                                    <?php echo htmlspecialchars($row['question_type']); ?>
                                                </option>
                                                <?php
												$stmt3 = $pdo_conn->query("SELECT * FROM question_type");
												while ($qt = $stmt3->fetch()) {
													if ($qt['question_type_id'] != $row['question_type_id']) {
														echo '<option value="' . $qt['question_type_id'] . '">' . htmlspecialchars($qt['question_type']) . '</option>';
													}
												}
												?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label"></label>
                                        <div class="controls">
                                            <!-- Multiple Choice -->
                                            <div id="opt11">
                                                A.) <input type="text" name="ans1"
                                                    value="<?php echo htmlspecialchars($a); ?>" size="60">
                                                <input name="correctm" value="A"
                                                    <?php if ($row['answer'] == 'A') echo 'checked'; ?>
                                                    type="radio"><br><br>

                                                B.) <input type="text" name="ans2"
                                                    value="<?php echo htmlspecialchars($b); ?>" size="60">
                                                <input name="correctm" value="B"
                                                    <?php if ($row['answer'] == 'B') echo 'checked'; ?>
                                                    type="radio"><br><br>

                                                C.) <input type="text" name="ans3"
                                                    value="<?php echo htmlspecialchars($c); ?>" size="60">
                                                <input name="correctm" value="C"
                                                    <?php if ($row['answer'] == 'C') echo 'checked'; ?>
                                                    type="radio"><br><br>

                                                D.) <input type="text" name="ans4"
                                                    value="<?php echo htmlspecialchars($d); ?>" size="60">
                                                <input name="correctm" value="D"
                                                    <?php if ($row['answer'] == 'D') echo 'checked'; ?>
                                                    type="radio"><br><br>
                                            </div>

                                            <!-- True/False -->
                                            <div id="opt12">
                                                <input name="correctt" value="Verdadero" type="radio"
                                                    <?php if ($row['answer'] == 'True') echo 'checked'; ?>>Verdadero<br><br>
                                                <input name="correctt" value="Falso" type="radio"
                                                    <?php if ($row['answer'] == 'False') echo 'checked'; ?>>
                                                Falso<br><br>
                                            </div>
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
									$question_text = $_POST['question'];
									$type = $_POST['question_type'];

									$answer_val = ($type == 2) ? $_POST['correctt'] : $_POST['correctm'];

									$stmt = $pdo_conn->prepare("UPDATE quiz_question SET question_text = :question, answer = :answer, question_type_id = :type WHERE quiz_question_id = :id");
									$stmt->execute([
										':question' => $question_text,
										':answer' => $answer_val,
										':type' => $type,
										':id' => $quiz_question_id
									]);

									if ($type != 2) {
										$stmt_del = $pdo_conn->prepare("DELETE FROM answer WHERE quiz_question_id = :id");
										$stmt_del->execute([':id' => $quiz_question_id]);

										$answers = [
											['ans' => $_POST['ans1'], 'choice' => 'A'],
											['ans' => $_POST['ans2'], 'choice' => 'B'],
											['ans' => $_POST['ans3'], 'choice' => 'C'],
											['ans' => $_POST['ans4'], 'choice' => 'D'],
										];

										$stmt_ins = $pdo_conn->prepare("INSERT INTO answer (quiz_question_id, answer_text, choices) VALUES (:qid, :text, :choice)");
										foreach ($answers as $a) {
											if (!empty($a['ans'])) {
												$stmt_ins->execute([
													':qid' => $quiz_question_id,
													':text' => $a['ans'],
													':choice' => $a['choice']
												]);
											}
										}
									}

									echo "<script>window.location='quiz_question.php?id=$get_id';</script>";
								}
								?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function() {
            var type = jQuery("#qtype").val();
            if (type == '1') {
                jQuery("#opt11").show();
                jQuery("#opt12").hide();
            } else if (type == '2') {
                jQuery("#opt11").hide();
                jQuery("#opt12").show();
            }

            jQuery("#qtype").change(function() {
                var x = jQuery(this).val();
                if (x == '1') {
                    jQuery("#opt11").show();
                    jQuery("#opt12").hide();
                } else if (x == '2') {
                    jQuery("#opt11").hide();
                    jQuery("#opt12").show();
                }
            });
        });
        </script>

        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>