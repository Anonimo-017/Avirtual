<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
        <div class="container-fluid">

            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a class="brand" href="#">
                Panel de estudiante Aula virtual
            </a>

            <div class="nav-collapse collapse">
                <ul class="nav pull-right">

                    <?php

                    $stmt = $pdo_conn->prepare("
                        SELECT firstname, lastname 
                        FROM student 
                        WHERE student_id = :student_id 
                        LIMIT 1
                    ");
                    $stmt->execute(['student_id' => (int)$session_id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $student_name = $row ? htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) : 'Estudiante';
                    ?>

                    <li class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user icon-large"></i>
                            <?php echo $student_name; ?>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="#profileModal" data-toggle="modal" data-remote="perfil_student.php">
                                    <i class="icon-picture"></i> info de Perfil
                                </a></li>
                            <li>
                                <a href="change_password_student.php">
                                    <i class="icon-circle"></i> Cambiar Contraseña
                                </a>
                            </li>
                            <li>
                                <a href="#myModal_student" data-toggle="modal">
                                    <i class="icon-picture"></i>Cambiar foto de perfil
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="logout.php">
                                    <i class="icon-signout"></i>&nbsp;salir
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Perfil del Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#profileModal').on('show.bs.modal', function(e) {
        var link = $(e.relatedTarget);
        $(this).find('.modal-body').load(link.data("remote"));
    });
});
</script>

<?php include('avatar_modal_student.php'); ?>