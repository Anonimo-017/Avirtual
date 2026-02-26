<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($get_id === false || $get_id === null) {
    die("Invalid class ID.");
}
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('quiz_link.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    try {
                        $class_query = $pdo_conn->prepare("SELECT * FROM teacher_class
                            LEFT JOIN class ON class.class_id = teacher_class.class_id
                            LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
                            WHERE teacher_class_id = :get_id");
                        $class_query->bindParam(':get_id', $get_id, PDO::PARAM_INT);
                        $class_query->execute();
                        $class_row = $class_query->fetch(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Error en la consulta: " . $e->getMessage());
                    }

                    if (!$class_row) {
                        die("Clase no encontrada.");
                    }
                    ?>
                    <ul class="breadcrumb">
                        <li><a
                                href="#"><?php echo htmlspecialchars((string)$class_row['class_name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a
                                href="#"><?php echo htmlspecialchars((string)$class_row['subject_code'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#">Año Escolar:
                                <?php echo htmlspecialchars((string)$class_row['school_year'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Practica de Examen</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">

                                <form action="delete_class_quiz.php<?php echo '?id=' . $get_id; ?>" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="">
                                        <a data-toggle="modal" href="#backup_delete" id="delete"
                                            class="btn btn-danger"><i class="icon-trash icon-large"></i></a>
                                        <?php include('modal_delete_class_quiz.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Titulo de Examen</th>
                                                <th>Descripcion</th>
                                                <th>TIEMPO DE EXAMEN (EN MINUTOS)</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            try {
                                                $query = $pdo_conn->prepare("SELECT * FROM class_quiz 
                                                    LEFT JOIN quiz ON quiz.quiz_id  = class_quiz.quiz_id
                                                    WHERE teacher_class_id = :get_id 
                                                    ORDER BY date_added DESC");
                                                $query->bindParam(':get_id', $get_id, PDO::PARAM_INT);
                                                $query->execute();

                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $id  = intval($row['class_quiz_id']);
                                            ?>
                                            <tr id="del<?php echo $id; ?>">
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars((string)$row['quiz_title'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars((string)$row['quiz_description'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo intval($row['quiz_time']) / 60; ?></td>
                                                <td><?php echo htmlspecialchars((string)$row['date_added'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>


                                            </tr>
                                            <?php }
                                            } catch (PDOException $e) {
                                                echo "Error al obtener los datos: " . $e->getMessage();
                                            } ?>
                                        </tbody>
                                    </table>
                                </form>

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