<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="dashboard_teacher.php">Panel docente</a>
            <div class="nav-collapse collapse">
                <ul class="nav pull-right">
                    <?php
                    $session_id = mysqli_real_escape_string($con, $session_id);
                    $query = mysqli_query($con, "SELECT * FROM teacher WHERE teacher_id = '$session_id'") or die(mysqli_error($con));
                    $row = mysqli_fetch_array($query);

                    if ($row) {
                        $firstname = htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8');
                        $lastname = htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8');
                        echo '<li class="dropdown">
                                  <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="icon-user icon-large"></i>' . $firstname . ' ' . $lastname . ' <i class="caret"></i>
                                  </a> 
                                  <ul class="dropdown-menu">
                                      <li>
                                          <a href="change_password_teacher.php"><i class="icon-circle"></i> Cambiar Contraseña</a>
                                          <a tabindex="-1" href="#myModal" data-toggle="modal"><i class="icon-picture"></i> Cambiar img de perfil</a>
                                          <a tabindex="-1" href="profile_teacher.php"><i class="icon-user"></i> Perfil</a>
                                      </li>
                                      <li class="divider"></li>
                                      <li><a tabindex="-1" href="logout.php"><i class="icon-signout"></i>&nbsp;Cerrar Sesión</a></li>
                                  </ul>
                              </li>';
                    } else {
                        echo '<li class="dropdown">
                                  <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="icon-user icon-large"></i> Usuario no encontrado <i class="caret"></i>
                                  </a>
                                  <ul class="dropdown-menu">
                                      <li><a tabindex="-1" href="#">&nbsp;Error</a></li>
                                  </ul>
                              </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>