<?php
$get_id = mysqli_real_escape_string($con, $get_id);

$class_query = mysqli_query($con, "SELECT
    teacher_class.school_year,
    class.class_name,
    subject.subject_code
    FROM teacher_class
    LEFT JOIN class ON class.class_id = teacher_class.class_id
    LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
    WHERE teacher_class.teacher_class_id = '$get_id'") or die("Error in SQL query: " . mysqli_error($con));

$class_row = mysqli_fetch_array($class_query);

if ($class_row) {
    $class_name = htmlspecialchars((string)$class_row['class_name'], ENT_QUOTES, 'UTF-8');
    $subject_code = htmlspecialchars((string)$class_row['subject_code'], ENT_QUOTES, 'UTF-8');
    $school_year = htmlspecialchars((string)$class_row['school_year'], ENT_QUOTES, 'UTF-8');
} else {
    $class_name = "Unknown Class";
    $subject_code = "Unknown Subject";
    $school_year = "Unknown Year";
}
?>

<ul class="breadcrumb">
    <li><a href="classes.php"><?php echo $class_name; ?></a> <span class="divider">/</span></li>
    <li><a href="subjects.php"><?php echo $subject_code; ?></a> <span class="divider">/</span></li>
    <li><a href="#">Año Escolar: <?php echo $school_year; ?></a> <span class="divider">/</span></li>
    <li><a href="#"><b>Mis Estudiantes</b></a></li>
</ul>