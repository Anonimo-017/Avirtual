<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('quiz_sidebar_teacher.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">

                    <ul class="breadcrumb">
                        <?php

						$session_id = mysqli_real_escape_string($con, $session_id);

						$school_year_query = mysqli_query($con, "SELECT * FROM school_year ORDER BY school_year DESC") or die(mysqli_error($con));
						$school_year_query_row = mysqli_fetch_array($school_year_query);
						if ($school_year_query_row) {
							$school_year = htmlspecialchars($school_year_query_row['school_year'], ENT_QUOTES, 'UTF-8');
						} else {
							$school_year = "N/A";
						}
						?>
                        <li><a href="#"><b>Mi Clase</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo $school_year; ?></a><span class="divider">/</span></li>
                        <li><a href="#"><b>Quiz</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-right"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="pull-right">
                                    <a href="add_quiz.php" class="btn btn-info"><i class="icon-plus-sign"></i> Agregar
                                        Examen</a>
                                    <td width="30"><a href="add_quiz_to_class.php" class="btn btn-success"><i
                                                class="icon-plus-sign"></i> Agregar Examen a Clase</a></td>
                                </div>

                                <form action="delete_quiz.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="">
                                        <a data-toggle="modal" href="#backup_delete" id="delete" class="btn btn-danger"
                                            name=""><i class="icon-trash icon-large"></i></a>
                                        <?php include('modal_delete_quiz.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Titulo de Examen</th>
                                                <th>Descripcion</th>
                                                <th>Fecha Añadida</th>
                                                <th>Preguntas</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

											$session_id = mysqli_real_escape_string($con, $session_id);

											$query = mysqli_query($con, "SELECT * FROM quiz WHERE teacher_id = '$session_id'  ORDER BY date_added DESC ") or die(mysqli_error($con));
											while ($row = mysqli_fetch_array($query)) {
												$id  = intval($row['quiz_id']);
												$quiz_title = htmlspecialchars($row['quiz_title'], ENT_QUOTES, 'UTF-8');
												$quiz_description = htmlspecialchars($row['quiz_description'], ENT_QUOTES, 'UTF-8');
												$date_added = htmlspecialchars($row['date_added'], ENT_QUOTES, 'UTF-8');
											?>
                                            <tr id="del<?php echo $id; ?>">
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo $quiz_title; ?></td>
                                                <td><?php echo $quiz_description; ?></td>
                                                <td><?php echo $date_added; ?></td>
                                                <td><a href="quiz_question.php<?php echo '?id=' . $id; ?>">Preguntas</a>
                                                </td>
                                                <td width="30"><a href="edit_quiz.php<?php echo '?id=' . $id; ?>"
                                                        class="btn btn-success"><i class="icon-pencil"></i></a></td>
                                            </tr>
                                            <?php } ?>
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