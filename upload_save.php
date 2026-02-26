<?php
require_once('session.php'); 
require_once('dbcon.php'); 

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filedesc = clean_input($_POST['desc']);
    $name = clean_input($_POST['name']);
    $id_class = $_POST['id_class'];

    $file = $_FILES['uploaded_file'];
    $filename = $file['name'];
    $filetmpname = $file['tmp_name'];
    $filesize = $file['size'];
    $fileerror = $file['error'];


    if ($filesize >= 1048576 * 5) {
        $_SESSION['error_message'] = "El archivo seleccionado excede el límite de tamaño de 5 MB.";
        header("Location: downloadable.php?id=" . $id_class);
        exit();
    }

    $allowed_extensions = array("jpg", "jpeg", "png", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "JPG");
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_extensions)) {
        $_SESSION['error_message'] = "Solo se permiten archivos con las siguientes extensiones: " . implode(", ", $allowed_extensions);
        header("Location: downloadable.php?id=" . $id_class);
        exit();
    }

    if ($fileerror === UPLOAD_ERR_OK) {
        $rd2 = mt_rand(1000, 9999) . "_File";
        $newname = "admin/uploads/" . $rd2 . "_" . $filename;

        if (move_uploaded_file($filetmpname, $newname)) {
            $uploaded_by_query = $pdo_conn->prepare("SELECT * FROM teacher WHERE teacher_id = :teacher_id");
            $uploaded_by_query->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
            $uploaded_by_query->execute();
            $uploaded_by_query_row = $uploaded_by_query->fetch(PDO::FETCH_ASSOC);
            $uploaded_by = $uploaded_by_query_row['firstname'] . " " . $uploaded_by_query_row['lastname'];

            try {
                $sql = "INSERT INTO files (fdesc, floc, fdatein, teacher_id, class_id, fname, uploaded_by) VALUES (:fdesc, :floc, NOW(), :teacher_id, :class_id, :fname, :uploaded_by)";
                $stmt = $pdo_conn->prepare($sql);
                $stmt->bindParam(':fdesc', $filedesc);
                $stmt->bindParam(':floc', $newname);
                $stmt->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
                $stmt->bindParam(':class_id', $id_class, PDO::PARAM_INT);
                $stmt->bindParam(':fname', $name);
                $stmt->bindParam(':uploaded_by', $uploaded_by);
                $stmt->execute();

                $name_notification = 'Add Material Descargable file name' . " " . '<b>' . $name . '</b>';
                $sql_notification = "INSERT INTO notification (teacher_class_id, notification, date_of_notification, link) VALUES (:teacher_class_id, :notification, NOW(), 'downloadable_student.php')";
                $stmt_notification = $pdo_conn->prepare($sql_notification);
                $stmt_notification->bindParam(':teacher_class_id', $id_class, PDO::PARAM_INT);
                $stmt_notification->bindParam(':notification', $name_notification);
                $stmt_notification->execute();

                $_SESSION['success_message'] = "Archivo subido correctamente.";
                header("Location: downloadable.php?id=" . $id_class);
                exit();
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Error al guardar la información del archivo en la base de datos: " . $e->getMessage();
                header("Location: downloadable.php?id=" . $id_class);
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Error al mover el archivo a la ubicación permanente.";
            header("Location: downloadable.php?id=" . $id_class);
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error al subir el archivo. Código de error: " . $fileerror;
        header("Location: downloadable.php?id=" . $id_class);
        exit();
    }
}
?>