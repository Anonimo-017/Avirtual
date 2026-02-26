<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('teacher_sidebar.php'); ?>
            <div class="span6" id="content">
                <div class="row-fluid">

                    <ul class="breadcrumb">
                        <?php
                        $school_year_query = mysqli_query($con, "select * from school_year order by school_year DESC") or die(mysqli_error($con));
                        $school_year_query_row = mysqli_fetch_array($school_year_query);
                        $school_year = $school_year_query_row['school_year'];
                        ?>
                        <li><a href="#">Profesores</a><span class="divider">/</span></li>
                        <li><a href="#"><b>Perfil</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left">Editar Perfil</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php
                                $session_id = mysqli_real_escape_string($con, $session_id);
                                $query = mysqli_query($con, "SELECT firstname, lastname, about FROM teacher WHERE teacher_id = '$session_id'") or die(mysqli_error($con));
                                $row = mysqli_fetch_array($query);
                                ?>
                                <form method="post" id="update_teacher" class="form-horizontal">

                                    <div class="control-group">
                                        <label class="control-label" for="inputEmail">Nombre:</label>
                                        <div class="controls">
                                            <input type="text" id="firstname" name="firstname"
                                                value="<?php echo htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="Nombre" required>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inputEmail">Apellido:</label>
                                        <div class="controls">
                                            <input type="text" id="lastname" name="lastname"
                                                value="<?php echo htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="Apellido" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="inputEmail">Acerca de mí:</label>
                                        <div class="controls">
                                            <textarea id="about" name="about"
                                                placeholder="Acerca de mí"><?php echo htmlspecialchars($row['about'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <button type="submit" name="update" class="btn btn-info"><i
                                                    class="icon-save"></i> Guardar Cambios</button>
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#myModal"><i class="icon-picture"></i>
                                                Cambiar imagen
                                                de
                                                perfil</button>
                                        </div>
                                    </div>
                                </form>


                                <?php
                                if (isset($_POST['update'])) {
                                    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
                                    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
                                    $about = mysqli_real_escape_string($con, $_POST['about']);

                                    $update_query = "UPDATE teacher SET firstname = '$firstname', lastname = '$lastname', about = '$about' WHERE teacher_id = '$session_id'";

                                    if (mysqli_query($con, $update_query)) {
                                        echo '<div class="alert alert-success">Perfil actualizado con éxito.</div>';
                                        echo "<script>window.location='profile_teacher.php';</script>";
                                    } else {
                                        echo '<div class="alert alert-error">Error al actualizar el perfil: ' . mysqli_error($con) . '</div>';
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <?php include('teacher_right_sidebar.php') ?>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('modal_upload_profile_picture.php'); ?>
    <?php include('script.php'); ?>
</body>

</html>