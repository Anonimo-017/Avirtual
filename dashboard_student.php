<?php
include('header_dashboard.php');
include('session.php');
include('dbcon.php');
?>

<body>
    <?php include('navbar_student.php'); ?>

    <div class="container-fluid">
        <div class="row-fluid">

            <?php include('student_sidebar.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">


                    <ul class="breadcrumb">
                        <?php
                        $stmt = $pdo_conn->query("SELECT school_year FROM school_year ORDER BY school_year DESC LIMIT 1");
                        $school_year_row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $school_year = $school_year_row['school_year'];
                        ?>
                        <li><a href="#"><b>Mi Clase</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo htmlspecialchars($school_year); ?></a></li>
                    </ul>

                    <div class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-right">
                                <?php
                                $stmt = $pdo_conn->prepare("
                                    SELECT COUNT(*) as count_classes
                                    FROM teacher_class_student tcs
                                    INNER JOIN teacher_class tc ON tc.teacher_class_id = tcs.teacher_class_id
                                    WHERE tcs.student_id = :student_id AND tc.school_year = :school_year
                                ");
                                $stmt->execute([
                                    'student_id' => (int)$session_id,
                                    'school_year' => $school_year
                                ]);
                                $count = $stmt->fetchColumn();
                                ?>
                                <span class="badge badge-info"><?php echo $count; ?></span>
                            </div>
                        </div>

                        <div class="block-content collapse in">
                            <div class="span12">
                                <ul id="da-thumbs" class="da-thumbs">

                                    <?php
                                    if ($count > 0) {
                                        $column_exists = $pdo_conn->query("SHOW COLUMNS FROM teacher LIKE 'thumbnails'")->rowCount() > 0;
                                        $select_thumbnails = $column_exists ? 't.thumbnails' : "'default_avatar.png' AS thumbnails";

                                        $stmt = $pdo_conn->prepare("
                                            SELECT 
                                                tc.teacher_class_id,
                                                c.class_name,
                                                s.subject_code,
                                                t.firstname,
                                                t.lastname,
                                                $select_thumbnails
                                            FROM teacher_class_student tcs
                                            INNER JOIN teacher_class tc ON tc.teacher_class_id = tcs.teacher_class_id
                                            INNER JOIN class c ON c.class_id = tc.class_id
                                            INNER JOIN subject s ON s.subject_id = tc.subject_id
                                            INNER JOIN teacher t ON t.teacher_id = tc.teacher_id
                                            WHERE tcs.student_id = :student_id
                                              AND tc.school_year = :school_year
                                        ");
                                        $stmt->execute([
                                            'student_id' => (int)$session_id,
                                            'school_year' => $school_year
                                        ]);

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $id = (int)$row['teacher_class_id'];
                                    ?>
                                    <li>
                                        <a href="my_classmates.php?id=<?php echo $id; ?>">
                                            <img src="admin/images/logo_class.png ?>" width="124" height="140"
                                                class="img-polaroid">
                                            <div>
                                                <span>
                                                    <p><?php echo htmlspecialchars($row['class_name']); ?></p>
                                                </span>
                                            </div>
                                        </a>
                                        <p class="class"><?php echo htmlspecialchars($row['class_name']); ?></p>
                                        <p class="subject"><?php echo htmlspecialchars($row['subject_code']); ?></p>
                                        <p class="subject">
                                            <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?>
                                        </p>
                                    </li>
                                    <?php
                                        }
                                    } else {
                                        ?>
                                    <div class="alert alert-info">
                                        <i class="icon-info-sign"></i>
                                        Actualmente no estás inscrito en ninguna clase.
                                    </div>
                                    <?php } ?>
                                </ul>
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