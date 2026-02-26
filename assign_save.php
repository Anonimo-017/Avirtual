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
    $id_class = $_POST['id_class'];
    $name = clean_input($_POST['name']);
    $filedesc = clean_input($_POST['desc']);
    $get_id = $_GET['id'];

    $file = $_FILES['uploaded_file'];
    $filename = $file['name'];
    $filetmpname = $file['tmp_name'];
    $filesize = $file['size'];
    $fileerror = $file['error'];

    if ($filename == "") {
        $name_notification = 'Add Assignment file name' . " " . '<b>' . $name . '</b>';

        try {
            $sql = "INSERT INTO assignment (fdesc, fdatein, teacher_id, class_id, fname) VALUES (:fdesc, NOW(), :teacher_id, :class_id, :fname)";
            $stmt = $pdo_conn->prepare($sql);
            $stmt->bindParam(':fdesc', $filedesc);
            $stmt->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
            $stmt->bindParam(':class_id', $id_class, PDO::PARAM_INT);
            $stmt->bindParam(':fname', $name);
            $stmt->execute();

            $sql_notification = "INSERT INTO notification (teacher_class_id, notification, date_of_notification, link) VALUES (:teacher_class_id, :notification, NOW(), 'assignment_student.php')";
            $stmt_notification = $pdo_conn->prepare($sql_notification);
            $stmt_notification->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
            $stmt_notification->bindParam(':notification', $name_notification);
            $stmt_notification->execute();

            header("Location: assignment.php?id=" . $get_id);
            exit();
        } catch (PDOException $e) {
            echo "Error al guardar la información en la base de datos: " . $e->getMessage();
        }
    } else {

        $rd2 = mt_rand(1000, 9999) . "_File";
        $newname = "admin/uploads/" . $rd2 . "_" . $filename;

        if (move_uploaded_file($filetmpname, $newname)) {
            $name_notification = 'Add Assignment file name' . " " . '<b>' . $name . '</b>';

            try {
                $sql = "INSERT INTO assignment (fdesc, floc, fdatein, teacher_id, class_id, fname) VALUES (:fdesc, :floc, NOW(), :teacher_id, :class_id, :fname)";
                $stmt = $pdo_conn->prepare($sql);
                $stmt->bindParam(':fdesc', $filedesc);
                $stmt->bindParam(':floc', $newname);
                $stmt->bindParam(':teacher_id', $session_id, PDO::PARAM_INT);
                $stmt->bindParam(':class_id', $id_class, PDO::PARAM_INT);
                $stmt->bindParam(':fname', $name);
                $stmt->execute();

                $sql_notification = "INSERT INTO notification (teacher_class_id, notification, date_of_notification, link) VALUES (:teacher_class_id, :notification, NOW(), 'assignment_student.php')";
                $stmt_notification = $pdo_conn->prepare($sql_notification);
                $stmt_notification->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
                $stmt_notification->bindParam(':notification', $name_notification);
                $stmt_notification->execute();

                header("Location: assignment.php?id=" . $get_id);
                exit();
            } catch (PDOException $e) {
                echo "Error al guardar la información en la base de datos: " . $e->getMessage();
            }
        } else {
            echo "Error al mover el archivo a la ubicación permanente.";
        }
    }
}
?>