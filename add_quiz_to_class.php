<?php
include('header_dashboard.php');
include('session.php');
include('dbcon.php');

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
						$stmt = $pdo_conn->query("SELECT * FROM school_year ORDER BY school_year DESC LIMIT 1");
						$school_year_row = $stmt->fetch(PDO::FETCH_ASSOC);
						$school_year = $school_year_row['school_year'];
						?>
                        <li><a href="#"><b>Mi Clase</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo htmlspecialchars($school_year); ?></a><span
                                class="divider">/</span></li>
                        <li><a href="#"><b>Cuestionarios</b></a></li>
                    </ul>
                    <!-- end breadcrumb -->

                    <!-- block -->
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-right"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="pull-right">
                                    <a href="teacher_quiz.php" class="btn btn-info"><i class="icon-arrow-left"></i>
                                        Back</a>
                                </div>

                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label" for="inputEmail">Examen</label>
                                        <div class="controls">
                                            <select name="quiz_id" required>
                                                <option value="">Seleccione un examen</option>
                                                <?php
												$stmt = $pdo_conn->prepare("SELECT * FROM quiz WHERE teacher_id = :teacher_id");
												$stmt->execute(['teacher_id' => $session_id]);
												while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
													$id = $row['quiz_id'];
													echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($row['quiz_title']) . '</option>';
												}
												?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword">Tiempo (en minutos)</label>
                                        <div class="controls">
                                            <input type="number" class="span3" name="time" id="inputPassword"
                                                placeholder="Test Time" required>
                                        </div>
                                    </div>

                                    <table class="table" id="question">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Clase</th>
                                                <th>Asignatura</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
											$stmt = $pdo_conn->prepare("
                                                SELECT tc.teacher_class_id, c.class_name, s.subject_code
                                                FROM teacher_class tc
                                                LEFT JOIN class c ON c.class_id = tc.class_id
                                                LEFT JOIN subject s ON s.subject_id = tc.subject_id
                                                WHERE tc.teacher_id = :teacher_id AND tc.school_year = :school_year
                                            ");
											$stmt->execute([
												'teacher_id' => $session_id,
												'school_year' => $school_year
											]);
											while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
												$id = $row['teacher_class_id'];
											?>
                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo htmlspecialchars($id); ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <div class="control-group">
                                        <div class="controls">
                                            <button name="save" type="submit" class="btn btn-info"><i
                                                    class="icon-save"></i> Guardar</button>
                                        </div>
                                    </div>
                                </form>

                                <?php
								if (isset($_POST['save'])) {
									$quiz_id = (int) $_POST['quiz_id'];
									$time = ((int) $_POST['time']) * 60;
									$ids = $_POST['selector'] ?? [];

									$name_notification = 'Add Practice Quiz file';

									if (!empty($ids)) {
										$insert_quiz = $pdo_conn->prepare("INSERT INTO class_quiz (teacher_class_id, quiz_time, quiz_id) VALUES (:teacher_class_id, :quiz_time, :quiz_id)");
										$insert_notification = $pdo_conn->prepare("INSERT INTO notification (teacher_class_id, notification, date_of_notification, link) VALUES (:teacher_class_id, :notification, NOW(), :link)");

										foreach ($ids as $teacher_class_id) {
											$teacher_class_id = (int) $teacher_class_id;
											$insert_quiz->execute([
												'teacher_class_id' => $teacher_class_id,
												'quiz_time' => $time,
												'quiz_id' => $quiz_id
											]);

											$insert_notification->execute([
												'teacher_class_id' => $teacher_class_id,
												'notification' => $name_notification,
												'link' => 'student_quiz_list.php'
											]);
										}
										echo "<script>window.location = 'teacher_quiz.php';</script>";
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