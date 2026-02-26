<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('class_sidebar.php'); ?>
            <div class="span3" id="adduser">
                <?php include('add_class.php'); ?>
            </div>
            <div class="span6" id="">
                <div class="row-fluid">
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Lista de carreras </div>
                            <div class="pull-right">
                                <a href="generate_class_report.php" class="btn btn-info"><i class="icon-print"></i>
                                    Generar Reporte PDF</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_class.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nombre de la carrera</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $class_query = mysqli_query($con, "select * from class") or die(mysqli_error($con));
                                            while ($class_row = mysqli_fetch_array($class_query)) {
                                                $id = intval($class_row['class_id']);
                                            ?>

                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td width="40"><a href="edit_class.php?id=<?php echo $id; ?>"
                                                        class="btn btn-success"><i class="icon-pencil icon-large"></i>
                                                    </a></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <a data-toggle="modal" href="#class_delete" id="delete" class="btn btn-danger"
                                        name="delete_classes"><i class="icon-trash icon-large"></i></a>
                                    <?php include('modal_delete.php'); ?>
                                </form>
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