<?php
include('header.php');
include('session.php');

$user_id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE user_id = '$user_id'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$firstname = $row['firstname'];
$lastname = $row['lastname'];
$username = $row['username'];
?>

<body style="padding-top: 70px;">
    <?php include('navbar.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Perfil</h2>

                <form method="post" action="actualizar_perfil.php">
                    <div class="form-group">
                        <label for="firstname">Nombre:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname"
                            value="<?php echo htmlspecialchars($firstname); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Apellido:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname"
                            value="<?php echo htmlspecialchars($lastname); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>


                    <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                </form>

                <hr>

                <h3>Cambiar Contraseña</h3>
                <form method="post" action="cambiar_password.php">
                    <div class="form-group">
                        <label for="current_password">Contraseña Actual:</label>
                        <input type="password" class="form-control" id="current_password" name="current_password"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Nueva Contraseña:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-warning">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
        <a href="dashboard.php" class="btn btn-success" type="submit">Regresar al Menú Principal</a>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>