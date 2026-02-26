<?php
include('dbcon.php');

if (isset($_POST['delete_subject'])) {
    if (isset($_POST['selector']) && is_array($_POST['selector'])) {
        $ids = $_POST['selector'];

        $escaped_ids = array_map(function($id) use ($con) {
            return mysqli_real_escape_string($con, $id);
        }, $ids);

        $id_string = "'" . implode("','", $escaped_ids) . "'";

        $query = "DELETE FROM subject WHERE subject_id IN ($id_string)";

        if (mysqli_query($con, $query)) {
            header("location: subjects.php");
        } else {
            echo "Error al eliminar las asignaturas: " . mysqli_error($con);
        }
    } else {
        echo "No se seleccionaron asignaturas para eliminar.";
    }
} else {
    echo "El formulario no se envió correctamente.";
} 
?>