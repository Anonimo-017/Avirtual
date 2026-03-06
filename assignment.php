<?php
require_once('header_dashboard.php');
require_once('session.php');
require_once('dbcon.php');

$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$get_id || $get_id <= 0) {
    die("ID de clase inválido.");
}
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('assignment_link.php'); ?>
            <div class="span6" id="content">
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
                        <li><a href="#"><b>Documentacion subida</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Fecha Subida</th>
                                            <th>Nombre de Archivo</th>
                                            <th>Descripcion</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        try {
                                            $query = $pdo_conn->prepare("SELECT * FROM assignment WHERE class_id = :get_id AND teacher_id = :session_id ORDER BY fdatein DESC");
                                            $query->bindParam(':get_id', $get_id, PDO::PARAM_INT);
                                            $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
                                            $query->execute();

                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                $id = $row['assignment_id'];
                                                $floc = $row['floc'];
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['fdatein']); ?></td>
                                            <td><?php echo htmlspecialchars($row['fname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['fdesc']); ?></td>
                                            <td width="250">
                                                <!-- Ver archivo -->
                                                <?php if ($floc != "") { ?>
                                                <a data-placement="bottom" title="Ver Archivo"
                                                    id="<?php echo $id; ?>view" class="btn btn-success"
                                                    href="<?php echo htmlspecialchars($floc); ?>" target="_blank">
                                                    <i class="icon-eye-open icon-large"></i>
                                                </a>
                                                <?php } ?>

                                                <!-- Descargar -->
                                                <?php
                                                        if ($floc == "") {
                                                        } else {
                                                        ?>
                                                <a data-placement="bottom" title="Descargar"
                                                    id="<?php echo $id; ?>download" class="btn btn-info"
                                                    href="<?php echo htmlspecialchars($row['floc']); ?>" download>
                                                    <i class="icon-download icon-large"></i>
                                                </a>
                                                <?php } ?>

                                                <!-- Eliminar -->
                                                <a data-placement="bottom" title="Eliminar"
                                                    id="<?php echo $id; ?>remove" class="btn btn-danger"
                                                    href="#<?php echo $id; ?>" data-toggle="modal">
                                                    <i class="icon-remove icon-large"></i>
                                                </a>
                                                <?php include('delete_assigment_modal.php'); ?>
                                            </td>
                                            <script type="text/javascript">
                                            $(document).ready(function() {
                                                $('#<?php echo $id; ?>download').tooltip('show');
                                                $('#<?php echo $id; ?>download').tooltip('hide');
                                            });
                                            </script>
                                            <script type="text/javascript">
                                            $(document).ready(function() {
                                                $('#<?php echo $id; ?>remove').tooltip('show');
                                                $('#<?php echo $id; ?>remove').tooltip('hide');
                                            });
                                            </script>
                                            <script type="text/javascript">
                                            $(document).ready(function() {
                                                $('#<?php echo $id; ?>view').tooltip('show');
                                                $('#<?php echo $id; ?>view').tooltip('hide');
                                            });
                                            </script>
                                        </tr>
                                        <?php
                                            }
                                        } catch (PDOException $e) {
                                            die("Error en la consulta: " . $e->getMessage());
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include('assignment_sidebar.php') ?>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>