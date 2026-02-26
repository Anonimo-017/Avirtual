<?php
include('admin/dbcon.php');
include('session.php');

$maxFileSize = 10000000;
$uploadDir = 'admin/uploads/';

try {
    if (isset($_POST['change'])) {

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];

            if ($fileSize > $maxFileSize) {
                throw new Exception("File size exceeds the maximum limit of " . ($maxFileSize / 1000000) . "MB.");
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Only JPEG, PNG, and GIF images are allowed.");
            }

            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

            $newFileName = preg_replace("/[^a-zA-Z0-9._-]/", "", basename(uniqid('', true) . '.' . $fileExt));

            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception("Failed to create the upload directory.");
                }
            }

            $fileDestination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $location = $fileDestination;
            } else {
                throw new Exception("Failed to move the uploaded file.");
            }

            $stmt = $pdo_conn->prepare("
                UPDATE student
                SET location = :location
                WHERE student_id = :student_id
            ");
            $stmt->execute([
                'location' => $location,
                'student_id' => $session_id
            ]);

            header("Location: dashboard_student.php");
            exit();
        } else {
            throw new Exception("No file was uploaded or an error occurred during upload.");
        }
    }
} catch (Exception $e) {
    error_log("Error updating profile picture: " . $e->getMessage()); 
    echo "<p>Error updating profile picture: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}