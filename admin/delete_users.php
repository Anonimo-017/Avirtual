<?php
include('dbcon.php');

if (isset($_POST['delete_user'])) {
    if (isset($_POST['selector']) && is_array($_POST['selector'])) {
        $ids = $_POST['selector'];

        $escaped_ids = array_map(function($id) use ($con) {
            return mysqli_real_escape_string($con, $id);
        }, $ids);

        $id_string = "'" . implode("','", $escaped_ids) . "'";

        $query = "DELETE FROM users WHERE user_id IN ($id_string)";

        if (mysqli_query($con, $query)) {
            header("location: admin_user.php");
            exit(); 
        } else {
            echo "Error al eliminar los usuarios: " . mysqli_error($con);
        }
    } else {
        echo "No se seleccionaron usuarios para eliminar.";
    }
} else {
    echo "El formulario no se envió correctamente.";
}
?>