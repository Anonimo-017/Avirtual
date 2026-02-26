<div class="forms-gen">
    <form id="login_form1" class="form-signin" method="post">
        <h3 class="form-signin-heading"><i class="icon-lock"></i> Iniciar Sesión</h3>
        <input type="text" class="input-block-level" id="username" name="username" placeholder="Usuario" required>
        <input type="password" class="input-block-level" id="password" name="password" placeholder="Contraseña"
            required>
        <button data-placement="right" title="Click Here to Iniciar Sesión" id="signin" name="login"
            class="btn btn-info" type="submit"><i class="icon-signin icon-large"></i> Iniciar Sesión</button>
        <script type="text/javascript">
        $(document).ready(function() {
            $('#signin').tooltip('show');
            $('#signin').tooltip('hide');
        });
        </script>
    </form>
    <script>
    jQuery(document).ready(function() {
        jQuery("#login_form1").submit(function(e) {
            e.preventDefault();
            var formData = jQuery(this).serialize();
            $.ajax({
                type: "POST",
                url: "login.php",
                data: formData,
                success: function(html) {
                    if (html == 'true') {
                        $.jGrowl("Cargando Archivo, Espere Por Favor......", {
                            sticky: true
                        });
                        $.jGrowl("Bienvenido", {
                            header: 'Inicio correcto'
                        });
                        var delay = 1000;
                        setTimeout(function() {
                            window.location = 'dashboard_teacher.php'
                        }, delay);
                    } else if (html == 'true_student') {
                        $.jGrowl("Bienvenido", {
                            header: 'Acceso correcto'
                        });
                        var delay = 1000;
                        setTimeout(function() {
                            window.location = 'dashboard_student.php'
                        }, delay);
                    } else {
                        $.jGrowl("Porfavor revise su Usuario y Contraseña", {
                            header: 'Inicio de Sesión Fallido'
                        });
                    }
                }
            });
            return false;
        });
    });
    </script>

    <div id="button_form" class="form-signin">
        <h3 class="form-signin-heading"><i class="icon-edit"></i>Nuevo aqui registrate</h3>
        <hr>

        <button data-placement="top" title="Iniciar Sesión as Student" id="signin_student"
            onclick="window.location='signup_student.php'" id="btn_student" name="login" class="btn btn-info"
            type="submit">Soy Estudiante</button>
        <div class="pull-right">
            <button data-placement="top" title="Iniciar Sesión as Teacher" id="signin_teacher"
                onclick="window.location='signup_teacher.php'" name="login" class="btn btn-info" type="submit">Soy
                Profesor</button>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#signin_student').tooltip('show');
        $('#signin_student').tooltip('hide');
    });
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#signin_teacher').tooltip('show');
        $('#signin_teacher').tooltip('hide');
    });
    </script>
</div>

<style>
@media (max-width: 680px) {
    .forms-gen {
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 10px;
    }

    .form-signin {
        width: 90%;
        margin-bottom: 20px;
    }
}
</style>