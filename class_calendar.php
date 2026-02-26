<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$get_id || $get_id <= 0) {
    die("ID de clase inválido.");
}

require_once('dbcon.php');
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('calendar_sidebar.php'); ?>
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
                        <li><a href="#"><?php echo htmlspecialchars((string)$class_row['class_name']); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#"><?php echo htmlspecialchars((string)$class_row['subject_code']); ?></a> <span
                                class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo htmlspecialchars($class_row['school_year']); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Mi Calendario de Clases</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">

                        <div class="block-content collapse in">
                            <div class="span8">

                                <div class="navbar navbar-inner block-header">
                                    <div class="muted pull-left">Calendario</div>
                                </div>
                                <div id='calendar'></div>
                            </div>

                            <div class="span4">
                                <?php include('add_class_event.php'); ?>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
    <?php include('class_calendar_script.php'); ?>
</body>

</html>