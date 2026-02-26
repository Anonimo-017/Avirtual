<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('user_log_sidebar.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Historial de usuarios logueados</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form method="GET" action="user_log.php" class="form-inline">
                                    <label for="start_date">Fecha de Inicio:</label>
                                    <input type="date" id="start_date" name="start_date" class="input-small"
                                        value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                                    <label for="end_date">Fecha de Fin:</label>
                                    <input type="date" id="end_date" name="end_date" class="input-small"
                                        value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">

                                    <label for="username">Usuario:</label>
                                    <input type="text" id="username" name="username" class="input-medium"
                                        placeholder="Nombre de usuario"
                                        value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">

                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <a href="report_user_log.php<?php
																$params = array();
																if (isset($_GET['start_date'])) $params[] = 'start_date=' . urlencode($_GET['start_date']);
																if (isset($_GET['end_date'])) $params[] = 'end_date=' . urlencode($_GET['end_date']);
																if (isset($_GET['username'])) $params[] = 'username=' . urlencode($_GET['username']);
																echo empty($params) ? '' : '?' . implode('&', $params);
																?>" class="btn btn-info"><i class="icon-print"></i> Generar Reporte PDF</a>
                                </form>
                                <hr>
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                    <thead>
                                        <tr>
                                            <th>Fecha Inicio de Sesión</th>
                                            <th>Fecha Cierre de Sesión</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($con, $_GET['start_date']) : '';
										$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($con, $_GET['end_date']) : '';
										$username = isset($_GET['username']) ? mysqli_real_escape_string($con, $_GET['username']) : '';

										$query = "SELECT * FROM user_log WHERE 1=1"; // Start with a basic query

										if (!empty($start_date)) {
											$query .= " AND login_date >= '$start_date'";
										}
										if (!empty($end_date)) {
											$query .= " AND login_date <= '$end_date'";
										}
										if (!empty($username)) {
											$query .= " AND username LIKE '%$username%'";
										}

										$query .= " ORDER BY user_log_id DESC";

										$user_query = mysqli_query($con, $query) or die(mysqli_error($con));
										while ($row = mysqli_fetch_array($user_query)) {
											$id = $row['user_log_id'];
										?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['login_date']); ?></td>
                                            <td><?php echo htmlspecialchars($row['logout_date']); ?></td>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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