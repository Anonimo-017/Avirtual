<?php
include('header_dashboard.php');
include('session.php');
include('admin/dbcon.php');

$get_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($get_id <= 0) {
    die("ID de clase inválido.");
}
?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('subject_overview_link_student.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    $stmt_class = $pdo_conn->prepare("
                        SELECT tc.teacher_class_id, c.class_name, s.subject_code
                        FROM teacher_class tc
                        LEFT JOIN class c ON c.class_id = tc.class_id
                        LEFT JOIN subject s ON s.subject_id = tc.subject_id
                        WHERE tc.teacher_class_id = :id
                        LIMIT 1
                    ");
                    $stmt_class->execute(['id' => $get_id]);
                    $class_row = $stmt_class->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo htmlspecialchars($class_row['class_name'] ?? 'Desconocido'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><?php echo htmlspecialchars($class_row['subject_code'] ?? 'Desconocido'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Descripción de Asignatura</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php
                                $stmt_teacher = $pdo_conn->prepare("
                                    SELECT t.firstname, t.lastname, t.location
                                    FROM teacher_class tc
                                    LEFT JOIN teacher t ON t.teacher_id = tc.teacher_id
                                    WHERE tc.teacher_class_id = :id
                                    LIMIT 1
                                ");
                                $stmt_teacher->execute(['id' => $get_id]);
                                $teacher = $stmt_teacher->fetch(PDO::FETCH_ASSOC);
                                ?>
                                Instructor:
                                <strong><?php echo htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']); ?></strong>
                                <br>
                                <?php if (!empty($teacher['location'])): ?>
                                <img id="avatar" class="img-polaroid"
                                    src="<?php echo htmlspecialchars($teacher['location']); ?>" alt="Avatar"
                                    width="150">
                                <?php endif; ?>
                                <hr>

                                <?php
                                $stmt_overview = $pdo_conn->prepare("
                                    SELECT content
                                    FROM class_subject_overview
                                    WHERE teacher_class_id = :id
                                    LIMIT 1
                                ");
                                $stmt_overview->execute(['id' => $get_id]);
                                $overview = $stmt_overview->fetch(PDO::FETCH_ASSOC);

                                if ($overview && !empty($overview['content'])) {
                                    echo $overview['content'];
                                } else {
                                    echo "<em>No hay descripción disponible.</em>";
                                }
                                ?>
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