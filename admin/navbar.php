<div class="barnav">
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <span class="brand" href="#"> Panel Administrativo</span>

                <div class="nav-collapse collapse">
                    <ul class="nav pull-right">
                        <br>
                        <?php
                        $query = mysqli_query($con, "select * from users where user_id = '$session_id'") or die(mysqli_error($con));
                        if ($row = mysqli_fetch_array($query)) {
                        ?>
                        <li class="dropdown">
                            <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                <i
                                    class="icon-user icon-large"></i><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname'], ENT_QUOTES, 'UTF-8');  ?>
                                <i class="caret"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a tabindex="-1" href="perfil.php">Perfil</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a tabindex="-1" href="logout.php"><i class="icon-signout"></i>&nbsp;Cerrar
                                        Sesión</a>
                                </li>
                            </ul>
                        </li>
                        <?php
                        } else {
                            echo "<li><a>Usuario no encontrado</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>