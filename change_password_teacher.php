<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('change_password_sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <ul class="breadcrumb">
                        <?php
                        $school_year_query = mysqli_query($con, "select * from school_year order by school_year DESC") or die(mysqli_error($con));
                        $school_year_query_row = mysqli_fetch_array($school_year_query);
                        $school_year = $school_year_query_row['school_year'];
                        ?>
                        <li><a href="#"><b>Cambiar Contraseña</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar:
                                <?php echo htmlspecialchars($school_year_query_row['school_year'], ENT_QUOTES, 'UTF-8'); ?></a>
                        </li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="" class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="alert alert-info"><i class="icon-info-sign"></i>Favor de llenar los
                                    siguientes campos</div>
                                <?php
                                $session_id = mysqli_real_escape_string($con, $session_id);
                                $query = mysqli_query($con, "select * from teacher where teacher_id = '$session_id'") or die(mysqli_error($con));
                                $row = mysqli_fetch_array($query);
                                ?>

                                <form method="post" id="change_password" class="form-horizontal">
                                    <div class="control-group">
                                        <label class="control-label" for="inputEmail">Contraseña actual</label>
                                        <div class="controls">
                                            <input type="password" id="current_password" name="current_password"
                                                placeholder="Introduce tu actual contraseña" required>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword">Nueva contraseña</label>
                                        <div class="controls">
                                            <input type="password" id="new_password" name="new_password"
                                                placeholder="Introduce tu nueva contraseña" required>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword">Repite tu nueva
                                            contraseña</label>
                                        <div class="controls">
                                            <input type="password" id="retype_password" name="retype_password"
                                                placeholder="Repite tu nueva contraseña" required>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <button type="submit" class="btn btn-info"><i class="icon-save"></i>
                                                Guardar</button>
                                        </div>
                                    </div>
                                </form>

                                <script>
                                    jQuery(document).ready(function() {
                                        jQuery("#change_password").submit(function(e) {
                                            e.preventDefault();

                                            var current_password = jQuery('#current_password').val();
                                            var new_password = jQuery('#new_password').val();
                                            var retype_password = jQuery('#retype_password').val();

                                            if (new_password != retype_password) {
                                                $.jGrowl(
                                                    "New password does not match with retyped password", {
                                                        header: 'Change Password Failed'
                                                    });
                                            } else {
                                                var formData = jQuery(this).serialize();
                                                $.ajax({
                                                    type: "POST",
                                                    url: "update_password.php",
                                                    data: formData,
                                                    success: function(response) {
                                                        // Handle the response from update_password.php
                                                        if (response ===
                                                            "Contraseña cambiada con éxito") {
                                                            $.jGrowl(
                                                                "Your password has been successfully changed", {
                                                                    header: 'Change Password Success'
                                                                });
                                                            var delay = 2000;
                                                            setTimeout(function() {
                                                                window.location =
                                                                    'dashboard_teacher.php';
                                                            }, delay);
                                                        } else if (response ===
                                                            "Contraseña incorrecta, favor de verificarla"
                                                        ) {
                                                            $.jGrowl(
                                                                "Incorrect current password", {
                                                                    header: 'Change Password Failed'
                                                                });
                                                        } else {
                                                            $.jGrowl("An error occurred: " +
                                                                response, {
                                                                    header: 'Change Password Failed'
                                                                });
                                                        }
                                                    },
                                                    error: function(xhr, status, error) {
                                                        $.jGrowl("AJAX error: " + error, {
                                                            header: 'Change Password Failed'
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                    <!-- /block -->
                </div>




            </div>

        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>