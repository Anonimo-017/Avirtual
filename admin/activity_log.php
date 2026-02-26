<?php
include('header.php');
include('session.php');
?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('activity_log_sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left" style="text-align: center;">
                                <h3>
                                    Historial de actividades administrativas</h3>
                            </div>
                            <div class=" pull-right">
                                <form method="GET" action="activity_log.php" class="form-inline">
                                    <h4>Generar reportes</h4>
                                    <input type="text" name="search" class="input-medium" placeholder="Buscar...">
                                    <button type="submit" class="btn btn-primary">Buscar Usuario o
                                        actividad</button>
                                    <a href="Report_activity_pdf.php<?php echo isset($_GET['search']) ? '?search=' . htmlspecialchars($_GET['search']) : ''; ?>"
                                        class="btn btn-info"><i class="icon-print"></i> Generar Reporte PDF</a>
                                </form>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <h5>Historial de actividades administrativas</h5>
                            <div class="span12">
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $searchKeyword = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
                                        $query = "SELECT * FROM activity_log";
                                        if (!empty($searchKeyword)) {
                                            $query .= " WHERE username LIKE '%$searchKeyword%' OR action LIKE '%$searchKeyword%'";
                                        }
                                        $query .= " ORDER BY date DESC";
                                        $result = mysqli_query($con, $query) or die(mysqli_error($con));
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                            <td><?php echo htmlspecialchars($row['action']); ?></td>
                                        </tr>
                                        <?php } ?>
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

</html>