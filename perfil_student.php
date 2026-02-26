<?php
include('session.php');
include('dbcon.php');

try {
    $stmt = $pdo_conn->prepare("
        SELECT student_id, firstname, lastname, username, location
        FROM student
        WHERE student_id = :student_id
        LIMIT 1
    ");
    $stmt->execute(['student_id' => (int)$session_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "<p>Error: Student not found.</p>";
        exit;
    }

    $firstname = htmlspecialchars($student['firstname'], ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($student['lastname'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($student['username'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($student['location'], ENT_QUOTES, 'UTF-8');
    $student_id = htmlspecialchars($student['student_id'], ENT_QUOTES, 'UTF-8');
} catch (PDOException $e) {
    echo "<p>Error fetching student data: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<h1>Perfil del Estudiante</h1>

<div id="profile-display">
    <p><strong>Nombre:</strong> <?php echo $firstname . ' ' . $lastname; ?></p>
    <p><strong>Usuario:</strong> <?php echo $username; ?></p>
    <img id="profile-image-display" src="<?php echo $location; ?>" alt="Foto de Perfil" style="max-width: 100px;">
    <p>En caso de querer cambiar algun dato del perfil solicitalo al personal administrativo</p>
</div>

<script>
    $(document).ready(function() {
        $("#edit-profile-btn").click(function() {
            $("#profile-display").hide();
            $("#profile-edit").show();
        });

        $("#cancel-edit-btn").click(function() {
            $("#profile-edit").hide();
            $("#profile-display").show();
        });

        $("#image").change(function() {
            readURL(this);
        });

        $("#edit-profile-form").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "update_profile.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response === "success") {
                        alert("Perfil actualizado exitosamente");
                        $("#profileModal").find('.modal-body').load(
                            "perfil_student.php");
                    } else {
                        alert("Error al actualizar el perfil: " + response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                    alert(
                        "Ocurrió un error al actualizar el perfil. Por favor, inténtelo de nuevo."
                    );
                }
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result);
                    $('#image-preview').css('display', 'inline');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    });
</script>