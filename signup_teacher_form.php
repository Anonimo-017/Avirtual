<div>
    <form id="signin_teacher" class="form-signin" method="post">
        <h3 class="form-signin-heading"><i class="icon-lock"></i> Registrar como Profesor</h3>
        <input type="text" class="input-block-level" name="firstname" placeholder="Nombres" required>
        <input type="text" class="input-block-level" name="lastname" placeholder="Apellidos" required>
        <label>Departamento - Sede</label>
        <select name="department_id" required>
            <option value="">Seleccionar Departamento</option>
            <?php
            $query = mysqli_query($con, "SELECT * FROM department ORDER BY department_name") or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($query)) {
            ?>
            <option value="<?php echo $row['department_id']; ?>">
                <?php echo htmlspecialchars($row['department_name']); ?></option>
            <?php
            }
            ?>
        </select>
        <input type="text" class="input-block-level" id="username" name="username" placeholder="Usuario" required>
        <input type="password" class="input-block-level" id="password" name="password" placeholder="Contraseña"
            required>
        <input type="password" class="input-block-level" id="cpassword" name="cpassword" placeholder="Repita Contraseña"
            required>
        <button id="signin" name="login" class="btn btn-info" type="submit"><i class="icon-check icon-large"></i>
            Registrar</button>
        <a onclick="window.location='index.php'" id="btn_login" name="login" class="btn" type="submit"><i
                class="icon-signin icon-large"></i> Ya tienes cuenta inicia sesion</a>
    </form>
</div>

<script>
jQuery(document).ready(function() {
    jQuery("#signin_teacher").submit(function(e) {
        e.preventDefault();
        var firstname = jQuery('input[name="firstname"]').val();
        var lastname = jQuery('input[name="lastname"]').val();
        var department_id = jQuery('select[name="department_id"]').val();
        var username = jQuery('#username').val();
        var password = jQuery('#password').val();
        var cpassword = jQuery('#cpassword').val();

        if (password == cpassword) {
            var formData = jQuery(this).serialize();
            $.ajax({
                type: "POST",
                url: "teacher_signup.php",
                data: formData,
                success: function(html) {
                    if (html == 'true') {
                        $.jGrowl("Registro exitoso.", {
                            header: 'Éxito'
                        });
                        setTimeout(function() {
                            window.location = 'index.php'
                        }, 2000);
                    } else if (html == 'username_exists') {
                        $.jGrowl("El nombre de usuario ya existe. Por favor, elige otro.", {
                            header: 'Error'
                        });
                    } else {
                        $.jGrowl("Error en el registro. Inténtalo de nuevo.", {
                            header: 'Error'
                        });
                    }
                },
                error: function() {
                    $.jGrowl("Error de conexión. Inténtalo de nuevo más tarde.", {
                        header: 'Error'
                    });
                }
            });
        } else {
            $.jGrowl("Las contraseñas no coinciden.", {
                header: 'Error'
            });
        }
    });
});
</script>