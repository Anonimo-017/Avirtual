<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>

<body style="display: flex; flex-direction: column; min-height: 100vh; margin: 0; font-family: sans-serif;">
    <?php include('navbar_student.php'); ?>

    <main style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px;">
        <div style="max-width: 600px; width: 100%;">
            <ul class="breadcrumb">
                <?php
				$school_year_query = mysqli_prepare($con, "SELECT * FROM school_year ORDER BY school_year DESC");
				if ($school_year_query) {
					mysqli_stmt_execute($school_year_query);
					$school_year_result = mysqli_stmt_get_result($school_year_query);
					$school_year_query_row = mysqli_fetch_array($school_year_result);
					$school_year = htmlspecialchars($school_year_query_row['school_year'], ENT_QUOTES, 'UTF-8');
					mysqli_stmt_close($school_year_query);
				} else {
					die("Prepare failed: " . mysqli_error($con));
				}
				?>
                <li><a href="#"><b>Cambiar Contraseña</b></a><span class="divider">/</span></li>
                <li><a href="#">Año Escolar: <?php echo $school_year; ?></a></li>
            </ul>

            <div id="block_bg" class="block">
                <div class="navbar navbar-inner block-header">
                    <div id="" class="muted pull-left"></div>
                </div>
                <div class="block-content collapse in">
                    <div class="span12">
                        <div class="alert alert-info"><i class="icon-info-sign"></i> Favor de llenar los
                            siguientes campos
                        </div>
                        <?php
						$query = mysqli_prepare($con, "SELECT * FROM student WHERE student_id = ?");
						if ($query) {
							mysqli_stmt_bind_param($query, "i", $session_id);
							mysqli_stmt_execute($query);
							$result = mysqli_stmt_get_result($query);
							$row = mysqli_fetch_array($result);
							$hashed_password = htmlspecialchars($row['password'], ENT_QUOTES, 'UTF-8');
							mysqli_stmt_close($query);
						} else {
							die("Prepare failed: " . mysqli_error($con));
						}
						?>

                        <form method="post" id="change_password" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label" for="inputEmail">Contraseña actual</label>
                                <div class="controls">
                                    <input type="hidden" id="password" name="password"
                                        value="<?php echo $hashed_password; ?>" placeholder="Current Password">
                                    <input type="password" id="current_password" name="current_password"
                                        placeholder="Current Password">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputPassword">Nueva contraseña</label>
                                <div class="controls">
                                    <input type="password" id="new_password" name="new_password"
                                        placeholder="New Password">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputPassword">Repite la nueva
                                    contraseña</label>
                                <div class="controls">
                                    <input type="password" id="retype_password" name="retype_password"
                                        placeholder="Re-type Password">
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i>
                                        Guardar</button>
                                </div>
                                <div class="controls">
                                    <a href="dashboard_student.php" class="btn btn-info"><i class="icon-arrow-left"></i>
                                        Volver</a>
                                </div>
                            </div>
                        </form>

                        <script>
                        jQuery(document).ready(function() {
                            jQuery("#change_password").submit(function(e) {
                                e.preventDefault();
                                var formData = jQuery(this).serialize();
                                $.ajax({
                                    type: "POST",
                                    url: "update_password_student.php",
                                    data: formData,
                                    success: function(response) {
                                        if (response === "success") {
                                            $.jGrowl(
                                                "Su contraseña ha sido cambiada exitosamente", {
                                                    header: 'Contraseña Cambiada Exitosamente'
                                                });
                                            var delay = 2000;
                                            setTimeout(function() {
                                                window.location =
                                                    'dashboard_student.php'
                                            }, delay);
                                        } else {
                                            $.jGrowl(
                                                "La modificación de la contraseña falló: " +
                                                response, {
                                                    header: 'La modificación de la contraseña falló'
                                                });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("AJAX error:", status, error);
                                        $.jGrowl(
                                            "Ocurrió un error durante la modificación de la contraseña. Por favor, inténtelo de nuevo.", {
                                                header: 'La modificación de la contraseña falló'
                                            });
                                    }
                                });
                            });
                        });
                        </script>

                    </div>
                </div>
            </div>
            <!-- /block -->
        </div>
    </main>

    <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>