<ul id="da-thumbs" class="da-thumbs">
    <?php
    $session_id = mysqli_real_escape_string($con, $session_id);
    $school_year = mysqli_real_escape_string($con, $school_year);

    $query = mysqli_query($con, "SELECT * FROM teacher_class
        LEFT JOIN class ON class.class_id = teacher_class.class_id
        LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
        WHERE teacher_id = '$session_id' AND school_year = '$school_year'") or logError(mysqli_error($con));

    $count = mysqli_num_rows($query);

    if ($count > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $id = intval($row['teacher_class_id']);
            $thumbnails = 'admin/images/logo_class.png';

            $class_name = ($row['class_name'] !== null) ? htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8') : '';
            $subject_code = ($row['subject_code'] !== null) ? htmlspecialchars($row['subject_code'], ENT_QUOTES, 'UTF-8') : '';

    ?>
    <li id="del<?php echo $id; ?>">
        <a href="my_students.php<?php echo '?id=' . $id; ?>">
            <img src="<?php echo $thumbnails; ?>" width="124" height="140" class="img-polaroid"
                alt="<?php echo $class_name; ?>">
            <div>
                <span>
                    <p><?php echo $class_name; ?></p>
                </span>
            </div>
        </a>
        <p class="class"><?php echo $class_name; ?></p>
        <p class="subject"><?php echo $subject_code; ?></p>
        <a href="#<?php echo $id; ?>" data-toggle="modal"><i class="icon-trash"></i> Eliminar</a>
    </li>
    <?php include("delete_class_modal.php"); ?>
    <?php }
    } else { ?>
    <div class="alert alert-info"><i class="icon-info-sign"></i>
        <p>No classes found for this teacher and school year.</p>
    </div>
    <?php } ?>
</ul>

<?php
function logError($errorMessage)
{
    error_log("Database Error: " . $errorMessage, 0);
}
?>