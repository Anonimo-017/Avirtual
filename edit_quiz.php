<?php
include('header_dashboard.php');
include('session.php');

$get_id = $_GET['id'];
include('dbcon.php'); // $pdo
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
                        <li>
                            <a href="#">Año Escolar: <?php echo $school_year_query_row['school_year']; ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Quiz</b></a></li>
                    </ul>
                    <!-- end breadcrumb -->

                    <!-- block -->
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-right"></div>
                        </div>

                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="pull-right">
                                    <a href="teacher_quiz.php" class="btn btn-info">
                                        <i class="icon-arrow-left"></i> Back
                                    </a>
                                </div>

                                <?php
								// Obtener datos del quiz usando PDO
								$stmt = $pdo_conn->prepare("SELECT * FROM quiz WHERE quiz_id = :quiz_id");
								$stmt->execute([':quiz_id' => $get_id]);
								$row = $stmt->fetch();
								?>

                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label">Titulo de Examen</label>
                                        <div class="controls">
                                            <input type="hidden" name="quiz_id" value="<?php echo $row['quiz_id']; ?>">
                                            <input type="text" name="quiz_title"
                                                value="<?php echo $row['quiz_title']; ?>" placeholder="Titulo de Examen"
                                                required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Descripcion de Examen</label>
                                        <div class="controls">
                                            <input type="text" name="description"
                                                value="<?php echo $row['quiz_description']; ?>" class="span8"
                                                placeholder="Descripcion de Examen" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <button name="save" type="submit" class="btn btn-success">
                                                <i class="icon-save"></i> Guardar
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <?php
								if (isset($_POST['save'])) {
									$quiz_id = $_POST['quiz_id'];
									$quiz_title = $_POST['quiz_title'];
									$description = $_POST['description'];

									// Actualizar quiz con PDO
									$stmt = $pdo_conn->prepare(
										"UPDATE quiz SET quiz_title = :title, quiz_description = :description WHERE quiz_id = :quiz_id"
									);

									$stmt->execute([
										':title' => $quiz_title,
										':description' => $description,
										':quiz_id' => $quiz_id
									]);

									echo "<script>window.location='teacher_quiz.php';</script>";
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