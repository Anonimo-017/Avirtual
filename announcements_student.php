<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($get_id === false || $get_id === null) {
    die("Invalid class ID.");
}
?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('annoucement_link_student.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    $class_query = mysqli_prepare($con, "SELECT tc.teacher_class_id, c.class_name, s.subject_code
                        FROM teacher_class tc
                        LEFT JOIN class c ON c.class_id = tc.class_id
                        LEFT JOIN subject s ON s.subject_id = tc.subject_id
                        WHERE tc.teacher_class_id = ?");

                    if ($class_query) {
                        mysqli_stmt_bind_param($class_query, "i", $get_id);
                        mysqli_stmt_execute($class_query);
                        $class_result = mysqli_stmt_get_result($class_query);
                        $class_row = mysqli_fetch_array($class_result);

                        if ($class_row) {
                            $class_name = htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8');
                            $subject_code = htmlspecialchars($class_row['subject_code'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo $class_name; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><?php echo $subject_code; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><b>Avisos</b></a></li>
                    </ul>
                    <?php
                        } else {
                            echo "<div class='alert alert-danger'>Class not found.</div>";
                        }

                        mysqli_stmt_close($class_query);
                    } else {
                        die("Prepare failed: " . mysqli_error($con));
                    }
                    ?>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php
                                $query_announcement = mysqli_prepare($con, "SELECT teacher_class_announcements_id, content, date
                                    FROM teacher_class_announcements
                                    WHERE teacher_class_id = ?
                                    ORDER BY date DESC");

                                if ($query_announcement) {
                                    mysqli_stmt_bind_param($query_announcement, "i", $get_id);
                                    mysqli_stmt_execute($query_announcement);
                                    $result_announcement = mysqli_stmt_get_result($query_announcement);
                                    $count = mysqli_num_rows($result_announcement);

                                    if ($count > 0) {
                                        while ($row = mysqli_fetch_array($result_announcement)) {
                                            $id = htmlspecialchars($row['teacher_class_announcements_id'], ENT_QUOTES, 'UTF-8');
                                            $content = html_entity_decode($row['content']);
                                            $date = htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8');
                                ?>
                                <div class="post" id="del<?php echo $id; ?>">
                                    <?php echo $content; ?>
                                    <hr>
                                    <strong><i class="icon-calendar"></i> <?php echo $date; ?></strong>
                                </div>
                                <?php
                                        }
                                    } else {
                                        echo '<div class="alert alert-info"><i class="icon-info-sign"></i>Sin avisos</div>';
                                    }
                                    mysqli_stmt_close($query_announcement);
                                } else {
                                    die("Prepare failed: " . mysqli_error($con));
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