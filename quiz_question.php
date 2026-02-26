<?php
include('header_dashboard.php');
include('session.php');

$get_id = $_GET['id'];
include('dbcon.php');
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('quiz_sidebar_teacher.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">

                    <ul class="breadcrumb">
                        <?php
                        $stmt = $pdo_conn->query("SELECT * FROM school_year ORDER BY school_year DESC");
                        $school_year_query_row = $stmt->fetch();
                        ?>
                        <li><a href="#"><b>Mi Clase</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo $school_year_query_row['school_year']; ?></a><span
                                class="divider">/</span></li>
                        <li><a href="#"><b>Examen</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-right"></div>
                        </div>

                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="pull-right">
                                    <a href="teacher_quiz.php" class="btn btn-success"><i class="icon-arrow-left"></i>
                                        Back</a>
                                    <a href="add_question.php?id=<?php echo $get_id; ?>" class="btn btn-info"><i
                                            class="icon-plus-sign"></i>Agregar pregunta</a>
                                </div>

                                <form action="delete_quiz_question.php" method="post">
                                    <input type="hidden" name="get_id" value="<?php echo $get_id; ?>">

                                    <table cellpadding="0" cellspacing="0" border="0" class="table">
                                        <a data-toggle="modal" href="#backup_delete" id="delete"
                                            class="btn btn-danger"><i class="icon-trash icon-large"></i></a>
                                        <?php include('modal_delete_quiz_question.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Pregunta</th>
                                                <th>Tipo de Pregunta</th>
                                                <th>Respuesta</th>
                                                <th>Fecha</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $pdo_conn->prepare("
                                                SELECT qq.*, qt.question_type 
                                                FROM quiz_question qq
                                                LEFT JOIN question_type qt 
                                                ON qq.question_type_id = qt.question_type_id
                                                WHERE qq.quiz_id = :quiz_id
                                                ORDER BY qq.date_added DESC
                                            ");
                                            $stmt->execute([':quiz_id' => $get_id]);
                                            while ($row = $stmt->fetch()) {
                                                $id = $row['quiz_question_id'];
                                            ?>
                                            <tr id="del<?php echo $id; ?>">
                                                <td width="30">
                                                    <input id="optionsCheckbox" name="selector[]" type="checkbox"
                                                        value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo $row['question_text']; ?></td>
                                                <td><?php echo $row['question_type']; ?></td>
                                                <td><?php echo $row['answer']; ?></td>
                                                <td><?php echo $row['date_added']; ?></td>
                                                <td width="30">
                                                    <a href="edit_question.php?id=<?php echo $get_id; ?>&quiz_question_id=<?php echo $id; ?>"
                                                        class="btn btn-success">
                                                        <i class="icon-pencil"></i>
                                                    </a>
                                                </td>
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