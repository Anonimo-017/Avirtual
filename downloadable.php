<?php
require_once('header_dashboard.php');
require_once('session.php');
require_once('dbcon.php');

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$get_id || $get_id <= 0) {
    die("ID de clase inválido.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Material Descargable</title>
</head>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('downloadable_link.php'); ?>
            <div class="span6" id="content">
                <div class="row-fluid">
                    <?php
                    $sql = "SELECT
                                tc.teacher_class_id,
                                c.class_id,
                                c.class_name,
                                s.subject_code,
                                tc.school_year
                            FROM
                                teacher_class tc
                            LEFT JOIN
                                class c ON c.class_id = tc.class_id
                            LEFT JOIN
                                subject s ON s.subject_id = tc.subject_id
                            WHERE
                                tc.teacher_class_id = :get_id";

                    try {
                        $stmt = $pdo_conn->prepare($sql);

                        $stmt->bindParam(':get_id', $get_id, PDO::PARAM_INT);

                        $stmt->execute();

                        $class_row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$class_row) {
                            die("Clase no encontrada.");
                        }

                        $class_id = $class_row['class_id'];
                        $school_year = $class_row['school_year'];
                    } catch (PDOException $e) {
                        die("Error en la consulta: " . $e->getMessage());
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
                        <li><a href="#"><b>Material Descargable</b></a></li>
                    </ul>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div id="downloadable_table.php" class="span12">

                                <?php
                                $sql_files = "SELECT * FROM files WHERE class_id = :class_id ORDER BY fdatein DESC";
                                try {
                                    $stmt_files = $pdo_conn->prepare($sql_files);

                                    $stmt_files->bindParam(':class_id', $get_id, PDO::PARAM_INT);

                                    $stmt_files->execute();

                                    $files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);

                                    if (count($files) === 0) { ?>
                                <div class="alert alert-info"><i class="icon-info-sign"></i> Actualmente no has
                                    subido ningún material descargable.</div>
                                <?php } else { ?>
                                <form action="copy_file.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table">
                                        <thead>
                                            <tr>
                                                <th>Fecha Subida</th>
                                                <th>Nombre de Archivo</th>
                                                <th>Descripción</th>
                                                <th>Subido por</th>
                                                <th>Acciones</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($files as $file) {
                                                        $id = $file['file_id']; ?>
                                            <tr id="del<?php echo $id; ?>">
                                                <td><?php echo htmlspecialchars($file['fdatein']); ?></td>
                                                <td><?php echo htmlspecialchars($file['fname']); ?></td>
                                                <td><?php echo htmlspecialchars($file['fdesc']); ?></td>
                                                <td><?php echo htmlspecialchars($file['uploaded_by']); ?></td>
                                                <td width="40">
                                                    <a data-placement="bottom" title="Descargar"
                                                        href="<?php echo htmlspecialchars($file['floc']); ?>"
                                                        download><i class="icon-download icon-large"></i></a>
                                                    <a data-placement="bottom" title="Eliminar"
                                                        href="#delete_file_modal<?php echo $id; ?>"
                                                        data-toggle="modal"><i class="icon-remove icon-large"></i></a>
                                                    <div id="delete_file_modal<?php echo $id; ?>"
                                                        class="modal hide fade" tabindex="-1" role="dialog"
                                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×</button>
                                                            <h3 id="myModalLabel">Eliminar Archivo</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>¿Estás seguro de que quieres eliminar el archivo
                                                                "<?php echo htmlspecialchars($file['fname']); ?>"?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn" data-dismiss="modal"
                                                                aria-hidden="true">Cancelar</button>
                                                            <a href="delete_file.php?id=<?php echo $id; ?>&get_id=<?php echo $get_id; ?>"
                                                                class="btn btn-danger">Eliminar</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>
                                <?php }
                                } catch (PDOException $e) {
                                    die("Error en la consulta: " . $e->getMessage());
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('downloadable_sidebar.php'); ?>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>