<?php
include('header.php');
include('session.php');
?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('log_hist_sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <div class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Historial de inicio de sesión</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form method="GET" action="">
                                    <div class="row-fluid">
                                        <div class="span3">
                                            <label>Seleccionar fecha de inicio:</label>
                                            <input type="date" name="start_date" class="form-control"
                                                value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                                        </div>
                                        <div class="span3">
                                            <label>Fecha de fin:</label>
                                            <input type="date" name="end_date" class="form-control"
                                                value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                                        </div>
                                        <div class="span3">
                                            <label>Tipo de usuario:</label>
                                            <select name="user_type" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="student"
                                                    <?php if (isset($_GET['user_type']) && $_GET['user_type'] == 'student') echo 'selected'; ?>>
                                                    Estudiantes</option>
                                                <option value="teacher"
                                                    <?php if (isset($_GET['user_type']) && $_GET['user_type'] == 'teacher') echo 'selected'; ?>>
                                                    Docentes</option>
                                            </select>
                                        </div>
                                        <div class="span3">
                                            <label>Buscar usuario:</label>
                                            <input type="text" name="search_username" class="form-control"
                                                placeholder="Ingrese el nombre de usuario"
                                                value="<?php echo isset($_GET['search_username']) ? $_GET['search_username'] : ''; ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <a href="login_history.php" class="btn btn-default">Restablecer</a>
                                </form>
                                <form method="POST" action="generate_history_report.php">
                                    <input type="hidden" name="start_date"
                                        value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                                    <input type="hidden" name="end_date"
                                        value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                                    <input type="hidden" name="user_type"
                                        value="<?php echo isset($_GET['user_type']) ? $_GET['user_type'] : ''; ?>">
                                    <input type="hidden" name="search_username"
                                        value="<?php echo isset($_GET['search_username']) ? $_GET['search_username'] : ''; ?>">
                                    <button type="submit" name="generate_report" class="btn btn-success">Generar
                                        reporte</button>
                                </form>

                                <hr>

                                <?php
                                include('dbcon.php');

                                $student_query = "SELECT sl.*, s.username FROM student_log sl INNER JOIN student s ON sl.student_id = s.student_id";
                                $teacher_query = "SELECT tl.*, t.username FROM teacher_log tl INNER JOIN teacher t ON tl.teacher_id = t.teacher_id";

                                $where_clause = " WHERE 1=1 ";

                                if (isset($_GET['start_date']) && $_GET['start_date'] != '') {
                                    $start_date = mysqli_real_escape_string($con, $_GET['start_date']);
                                    $where_clause .= " AND login_time >= '$start_date' ";
                                }

                                if (isset($_GET['end_date']) && $_GET['end_date'] != '') {
                                    $end_date = mysqli_real_escape_string($con, $_GET['end_date']);
                                    $where_clause .= " AND login_time <= '$end_date' ";
                                }

                                $search_username = isset($_GET['search_username']) ? mysqli_real_escape_string($con, $_GET['search_username']) : '';
                                $student_username_clause = " AND s.username LIKE '%$search_username%'";
                                $teacher_username_clause = " AND t.username LIKE '%$search_username%'";

                                if (isset($_GET['user_type']) && $_GET['user_type'] != '') {
                                    $user_type = mysqli_real_escape_string($con, $_GET['user_type']);
                                    if ($user_type == 'student') {
                                        $student_query .= $where_clause . $student_username_clause;
                                        $teacher_query = "";
                                    } else {
                                        $teacher_query .= $where_clause . $teacher_username_clause;
                                        $student_query = "";
                                    }
                                } else {
                                    $student_query .= $where_clause . $student_username_clause;
                                    $teacher_query .= $where_clause . $teacher_username_clause;
                                }

                                ?>

                                <h2>Historial de inicio de sesión</h2>
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="logTable">
                                    <thead>
                                        <tr>
                                            <th>ID del usuario</th>
                                            <th>Usuario</th>
                                            <th>Tipo de usuario</th>
                                            <th>Inicio de sesión</th>
                                            <th>Cierre de sesión</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($student_query)) {
                                            $student_log_query = mysqli_query($con, $student_query . " ORDER BY login_time DESC") or die(mysqli_error($con));
                                            while ($student_log_row = mysqli_fetch_array($student_log_query)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($student_log_row['student_id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($student_log_row['username']) . "</td>";
                                                echo "<td>Estudiante</td>";
                                                echo "<td>" . htmlspecialchars($student_log_row['login_time']) . "</td>";
                                                echo "<td>" . htmlspecialchars($student_log_row['logout_time'] ? $student_log_row['logout_time'] : 'Aún en línea') . "</td>";
                                                echo "</tr>";
                                            }
                                        }

                                        if (!empty($teacher_query)) {
                                            $teacher_log_query = mysqli_query($con, $teacher_query . " ORDER BY login_time DESC") or die(mysqli_error($con));
                                            while ($teacher_log_row = mysqli_fetch_array($teacher_log_query)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($teacher_log_row['teacher_id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($teacher_log_row['username']) . "</td>";
                                                echo "<td>Docente</td>";
                                                echo "<td>" . htmlspecialchars($teacher_log_row['login_time']) . "</td>";
                                                echo "<td>" . htmlspecialchars($teacher_log_row['logout_time'] ? $teacher_log_row['logout_time'] : 'Aún en línea') . "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>