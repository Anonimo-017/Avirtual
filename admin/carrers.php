<?php include('header.php'); ?>
<?php include('general/session.php'); ?>

<body>
    <?php include('general/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('carrers/carrers_sidebar.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">
                    <a href="add_carrers.php" class="btn btn-info"><i class="icon-plus-sign icon-large"></i> Agregar
                        carrera</a>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Lista de Carreras</div>
                            <div class="pull-right">
                                <a href="carrers/generate_carrers_report.php" class="btn btn-info"><i
                                        class="icon-print"></i> Generar Reporte PDF</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form method="post" action="modal/delete_subject.php">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Codigo de la carrera</th>
                                                <th>Carrera</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $subject_query = mysqli_query($con, "select * from subject") or die(mysqli_error($con));
                                            while ($row = mysqli_fetch_array($subject_query)) {
                                                $id = intval($row['subject_id']);
                                            ?>
                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($row['subject_code'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['subject_title'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td width="30"><a href="edit_subject.php?id=<?php echo $id; ?>"
                                                        class="btn btn-success"><i class="icon-pencil"></i> </a></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <!-- Enlace al modal (simplificado) -->
                                    <a href="#myModal" data-toggle="modal" class="btn btn-danger">Eliminar</a>

                                    <!-- Modal (simplificado) -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel">Confirmar Eliminación</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Está seguro de que desea eliminar las asignaturas seleccionadas?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" name="delete_subject"
                                                        class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('pie/footer.php'); ?>
    </div>
    <?php include('general/script.php'); ?>
</body>

</html>