<?php
require_once('session.php');
require_once('dbcon.php');

$id = $_GET['id'];
$get_id = $_GET['get_id']; 

try {
    $sql = "DELETE FROM files WHERE file_id = :id";
    $stmt = $pdo_conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: downloadable.php?id=" . $get_id);
    exit();
} catch (PDOException $e) {
    echo "Error al eliminar el archivo: " . $e->getMessage();
}
?>