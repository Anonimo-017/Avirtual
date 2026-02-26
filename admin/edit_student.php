<?php
include('header.php');
include('session.php');

$get_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('student_sidebar.php'); ?>

            <div class="span3" id="adduser">
                <?php include('edit_students_form.php'); ?>
            </div>

            <div class="span6">
                <div class="row-fluid">
                    <!-- block -->
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Lista Estudiantes</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_student.php" method="post">
                                    <a data-toggle="modal" href="#student_delete" id="delete" class="btn btn-danger">
                                        <i class="icon-trash icon-large"></i>
                                    </a>
                                    <?php include('modal_delete.php'); ?>

                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nombre</th>
                                                <th>ID</th>
                                                <th>Año del Curso y Sección</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include('dbcon.php');

                                            $stmt = $pdo_conn->prepare("
                                            SELECT s.student_id, s.firstname, s.lastname, s.username, c.class_name
                                            FROM student s
                                            LEFT JOIN class c ON c.class_id = s.class_id
                                            ORDER BY s.student_id DESC
                                        ");
                                            $stmt->execute();
                                            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($students as $row) {
                                                $id = $row['student_id'];

                                                $firstname = ($row['firstname'] === null) ? '' : htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8');
                                                $lastname = ($row['lastname'] === null) ? '' : htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8');
                                                $username = ($row['username'] === null) ? '' : htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
                                                $class_name = ($row['class_name'] === null) ? '' : htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8');
                                            ?>
                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>

                                                <td><?php echo $firstname . " " . $lastname; ?></td>
                                                <td><?php echo $username; ?></td>
                                                <td width="100"><?php echo $class_name; ?></td>
                                                <td width="30">
                                                    <a href="edit_student.php?id=<?php echo $id; ?>"
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