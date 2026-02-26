<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">Añadir Usuario</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <form method="post">
                    <div class="control-group">
                        <div class="controls">
                            <input class="input focused" name="firstname" id="focusedInput" type="text"
                                placeholder="Nombres" required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input class="input focused" name="lastname" id="focusedInput" type="text"
                                placeholder="Apellidos" required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input class="input focused" name="username" id="focusedInput" type="text"
                                placeholder="Usuario" required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input class="input focused" name="password" id="focusedInput" type="password"
                                placeholder="Contraseña" required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <button name="save" class="btn btn-info"><i class="icon-plus-sign icon-large"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>

<?php
if (isset($_POST['save'])) {
    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'") or die(mysqli_error($con));
    $count = mysqli_num_rows($query);

    if ($count > 0) { ?>
<script>
alert('El nombre de usuario ya existe.');
</script>
<?php
    } else {
        mysqli_query($con, "INSERT INTO users (username, password, firstname, lastname) VALUES('$username', '$password', '$firstname', '$lastname')") or die(mysqli_error($con));

        mysqli_query($con, "INSERT INTO activity_log (date, username, action) VALUES(NOW(),'$user_username','Agregar admin $username')") or die(mysqli_error($con));
    ?>
<script>
window.location = "admin_user.php";
</script>
<?php
    }
}
?>