<?php
require_once('session.php');
require_once('dbcon.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$get_id = isset($_GET['get_id']) ? intval($_GET['get_id']) : 0;

if (!$id || $id <= 0) {
    die("ID de asignación inválido.");
}

if (!$get_id || $get_id <= 0) {
    die("ID de clase inválido.");
}

try {
    $sql = "DELETE FROM assignment WHERE assignment_id = :id";
    $stmt = $pdo_conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: assignment.php?id=" . $get_id);
    exit();
} catch (PDOException $e) {
    echo "Error al eliminar la Asignatura: " . $e->getMessage();
}
?>