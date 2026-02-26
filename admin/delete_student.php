<?php
include('dbcon.php');

if (isset($_POST['delete_student'])) {
    if (isset($_POST['selector']) && is_array($_POST['selector'])) {
        $ids = $_POST['selector'];

        $escaped_ids = array_map(function($id) use ($con) {
            return mysqli_real_escape_string($con, $id);
        }, $ids);

        $id_string = "'" . implode("','", $escaped_ids) . "'";

        $query = "DELETE FROM student WHERE student_id IN ($id_string)";
        $query2 = "DELETE FROM teacher_class_student WHERE student_id IN ($id_string)";

        if (mysqli_query($con, $query) && mysqli_query($con, $query2)) {
            header("location: students.php");
            exit();
        } else {
            echo "Error al eliminar los estudiantes: " . mysqli_error($con);
        }
    } else {
        echo "No se seleccionaron estudiantes para eliminar.";
    }
} else {
    echo "El formulario no se envió correctamente.";
}
?>