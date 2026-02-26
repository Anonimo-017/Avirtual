<?php
include('header_dashboard.php');
include('session.php');
$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post'])) {
    if (!isset($_POST['content']) || !isset($_POST['id'])) {
        die("Error: Missing POST parameters.");
    }

    $content = trim($_POST['content']);
    $id = intval($_POST['id']);

    include('dbcon.php');
    $stmt = mysqli_prepare($con, "UPDATE teacher_class_announcements SET content = ? WHERE teacher_class_announcements_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $content, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: announcements.php?id=" . $get_id);
            exit;
        } else {
            error_log("Error updating announcement: " . mysqli_error($con));
            die("Error: Could not update announcement. Please try again later.");
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Error preparing statement: " . mysqli_error($con));
        die("Error: Database error. Please try again later.");
    }

    mysqli_close($con);
}
?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('annoucement_link.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    include('dbcon.php');
                    $class_query = mysqli_query($con, "SELECT * FROM teacher_class
                                            LEFT JOIN class ON class.class_id = teacher_class.class_id
                                            LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
                                            WHERE teacher_class_id = '$get_id'") or die(mysqli_error($con));

                    $class_row = mysqli_fetch_array($class_query);
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
                                <a href="announcements.php<?php echo '?id=' . $get_id; ?>"><i
                                        class="icon-arrow-left icon-large"></i> Back</a>
                                <br><br>
                                <form method="post">
                                    <?php
                                    include('dbcon.php');
                                    $query_announcement = mysqli_query($con, "SELECT * FROM teacher_class_announcements
                                                                    WHERE teacher_id = '$session_id' AND teacher_class_id = '$get_id'
                                                                    ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));

                                    $row = mysqli_fetch_array($query_announcement);

                                    if ($row) {
                                        $id = intval($row['teacher_class_announcements_id']);
                                    ?>
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <textarea name="content"
                                        id="ckeditor_full"><?php echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    <br>
                                    <button name="post" class="btn btn-info"><i class="icon-check icon-large"></i>
                                        Post</button>
                                    <?php } else {
                                        echo "<p>No announcements found.</p>";
                                    }
                                    ?>
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