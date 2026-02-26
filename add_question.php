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

                    <!-- breadcrumb -->
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
                            <div id="" class="muted pull-right">
                                <a href="quiz_question.php?id=<?php echo $get_id; ?>" class="btn btn-success"><i
                                        class="icon-arrow-left"></i>Volver</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">

                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword">Pregunta</label>
                                        <div class="controls">
                                            <textarea name="question" id="ckeditor_full" required></textarea>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="inputEmail">Tipo de pregunta:</label>
                                        <div class="controls">
                                            <select id="qtype" name="question_type" required>
                                                <option value="">

                                                </option>
                                                <?php
                                                $stmt = $pdo_conn->query("SELECT * FROM question_type");
                                                while ($row = $stmt->fetch()) {
                                                    echo '<option value="' . $row['question_type_id'] . '">' . $row['question_type'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label"></label>
                                        <div class="controls">
                                            <div id="opt11">
                                                A: <input type="text" name="ans1" size="60"> <input name="answer"
                                                    value="A" type="radio"><br><br>
                                                B: <input type="text" name="ans2" size="60"> <input name="answer"
                                                    value="B" type="radio"><br><br>
                                                C: <input type="text" name="ans3" size="60"> <input name="answer"
                                                    value="C" type="radio"><br><br>
                                                D: <input type="text" name="ans4" size="60"> <input name="answer"
                                                    value="D" type="radio"><br><br>
                                            </div>
                                            <div id="opt12">
                                                <input name="correctt" value="True" type="radio">Verdadero<br /><br />
                                                <input name="correctt" value="False" type="radio">Falso<br /><br />
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
                                    $question = $_POST['question'];
                                    $type = $_POST['question_type'];

                                    if ($type == '2') {
                                        $stmt = $pdo_conn->prepare("
                                            INSERT INTO quiz_question (quiz_id, question_text, date_added, answer, question_type_id)
                                            VALUES (:quiz_id, :question, NOW(), :answer, :type)
                                        ");
                                        $stmt->execute([
                                            ':quiz_id' => $get_id,
                                            ':question' => $question,
                                            ':answer' => $_POST['correctt'],
                                            ':type' => $type
                                        ]);
                                    } else {
                                        $stmt = $pdo_conn->prepare("
                                            INSERT INTO quiz_question (quiz_id, question_text, date_added, answer, question_type_id)
                                            VALUES (:quiz_id, :question, NOW(), :answer, :type)
                                        ");
                                        $stmt->execute([
                                            ':quiz_id' => $get_id,
                                            ':question' => $question,
                                            ':answer' => $_POST['answer'],
                                            ':type' => $type
                                        ]);

                                        $quiz_question_id = $pdo_conn->lastInsertId();

                                        $answers = [
                                            ['ans' => $_POST['ans1'], 'choice' => 'A'],
                                            ['ans' => $_POST['ans2'], 'choice' => 'B'],
                                            ['ans' => $_POST['ans3'], 'choice' => 'C'],
                                            ['ans' => $_POST['ans4'], 'choice' => 'D'],
                                        ];

                                        $stmt = $pdo_conn->prepare("INSERT INTO answer (quiz_question_id, answer_text, choices) VALUES (:quiz_question_id, :answer_text, :choice)");

                                        foreach ($answers as $a) {
                                            if (!empty($a['ans'])) {
                                                $stmt->execute([
                                                    ':quiz_question_id' => $quiz_question_id,
                                                    ':answer_text' => $a['ans'],
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
                    <!-- /block -->

                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function() {
            jQuery("#opt11").hide();
            jQuery("#opt12").hide();
            jQuery("#opt13").hide();

            jQuery("#qtype").change(function() {
                var x = jQuery(this).val();
                if (x == '1') {
                    jQuery("#opt11").show();
                    jQuery("#opt12").hide();
                    jQuery("#opt13").hide();
                } else if (x == '2') {
                    jQuery("#opt11").hide();
                    jQuery("#opt12").show();
                    jQuery("#opt13").hide();
                } else {
                    jQuery("#opt11").hide();
                    jQuery("#opt12").hide();
                    jQuery("#opt13").hide();
                }
            });
        });
        </script>

        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>