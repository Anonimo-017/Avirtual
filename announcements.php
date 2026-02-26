<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$get_id || $get_id <= 0) {
    die("ID de clase inválido.");
}

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);

    return $data;
}

require_once('dbcon.php');
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('annoucement_link.php'); ?>
            <div class="span5" id="content">
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
                        <li><a href="#"><b>Avisos</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form method="post">
                                    <textarea name="content" id="ckeditor_full"></textarea>
                                    <br>
                                    <button name="post" class="btn btn-info"><i class="icon-check icon-large"></i>
                                        Enviar</button>
                                </form>
                            </div>

                            <?php
                            if (isset($_POST['post'])) {
                                $content = clean_input($_POST['content']);

                                try {
                                    $sql = "INSERT INTO teacher_class_announcements (teacher_class_id, teacher_id, content, date) VALUES (:teacher_class_id, :teacher_id, :content, NOW())";
                                    $stmt = $pdo_conn->prepare($sql);
                                    $stmt->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
                                    $stmt->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
                                    $stmt->bindParam(':content', $content);
                                    $stmt->execute();

                                    $sql_notification = "INSERT INTO notification (teacher_class_id, notification, date_of_notification, link) VALUES (:teacher_class_id, 'Add Annoucements', NOW(), 'announcements_student.php')";
                                    $stmt_notification = $pdo_conn->prepare($sql_notification);
                                    $stmt_notification->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
                                    $stmt_notification->execute();

                                    echo "<script>window.location = 'announcements.php?id=" . $get_id . "';</script>";
                                    exit();
                                } catch (PDOException $e) {
                                    echo "Error al guardar el anuncio: " . $e->getMessage();
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="span4 row-fluid">
                <div id="block_bg" class="block">
                    <div class="navbar navbar-inner block-header">
                        <div id="" class="muted pull-left"></div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <?php
                            try {
                                if (isset($_POST['delete_announcement'])) {
                                    $announcement_id = intval($_POST['announcement_id']);
                                    if ($announcement_id > 0) {
                                        $delete_sql = "DELETE FROM teacher_class_announcements WHERE teacher_class_announcements_id = :announcement_id AND teacher_id = :teacher_id";
                                        $delete_stmt = $pdo_conn->prepare($delete_sql);
                                        $delete_stmt->bindParam(':announcement_id', $announcement_id, PDO::PARAM_INT);
                                        $delete_stmt->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
                                        $delete_stmt->execute();

                                        echo "<script>window.location = 'announcements.php?id=" . $get_id . "';</script>";
                                        exit();
                                    }
                                }

                                $query_announcement = $pdo_conn->prepare("SELECT * FROM teacher_class_announcements
                                    WHERE teacher_id = :teacher_id AND teacher_class_id = :teacher_class_id ORDER BY date DESC");
                                $query_announcement->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
                                $query_announcement->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
                                $query_announcement->execute();

                                while ($row = $query_announcement->fetch(PDO::FETCH_ASSOC)) {
                                    $id = intval($row['teacher_class_announcements_id']);
                            ?>
                            <div class="post" id="del<?php echo $id; ?>">
                                <?php echo $row['content']; ?>

                                <hr>

                                <strong><i class="icon-calendar"></i>
                                    <?php echo htmlspecialchars($row['date']); ?></strong>

                                <div class="pull-right">
                                    <form method="post">
                                        <input type="hidden" name="announcement_id" value="<?php echo $id; ?>">
                                        <button class="btn btn-link" name="delete_announcement"><i
                                                class="icon-remove"></i></button>
                                    </form>
                                </div>

                                <div class="pull-right">
                                    <form method="post" action="edit_post.php?id=<?php echo $get_id; ?>">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <button class="btn btn-link" name="edit"><i class="icon-pencil"></i> </button>
                                    </form>
                                </div>

                            </div>
                            <?php
                                }
                            } catch (PDOException $e) {
                                echo "Error al mostrar los anuncios: " . $e->getMessage();
                            }
                            ?>
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