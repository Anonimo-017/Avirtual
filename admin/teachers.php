<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('teacher_sidebar.php'); ?>
            <div class="span3" id="adduser">
                <?php include('add_teacher.php'); ?>
            </div>
            <div class="span6" id="">
                <div class="row-fluid">
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Lista de docentes</div>
                            <div class="pull-right">
                                <a href="generate_teacher_report.php" class="btn btn-info"><i class="icon-print"></i>
                                    Generar Reporte PDF</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_teacher.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <a data-toggle="modal" href="#teacher_delete" id="delete" class="btn btn-danger"
                                            name="delete_teacher"><i class="icon-trash icon-large"></i></a>
                                        <?php include('modal_delete.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Foto</th>
                                                <th>Nombre</th>
                                                <th>Usuario</th>

                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $teacher_query = mysqli_query($con, "select * from teacher") or die(mysqli_error($con));
                                            while ($row = mysqli_fetch_array($teacher_query)) {
                                                $id = $row['teacher_id'];
                                                $teacher_stat = $row['teacher_stat'];
                                            ?>
                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td width="40"><img class="img-circle"
                                                        src="../<?php echo htmlspecialchars($row['location']); ?>"
                                                        height="50" width="50"></td>
                                                <td><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['username']); ?></td>

                                                <td width="50"><a href="edit_teacher.php<?php echo '?id=' . $id; ?>"
                                                        class="btn btn-success"><i class="icon-pencil"></i></a></td>
                                                <?php if ($teacher_stat == 'Activated') { ?>
                                                <td width="120"><a href="de_activate.php<?php echo '?id=' . $id; ?>"
                                                        class="btn btn-danger"><i class="icon-remove"></i>
                                                        Desactivar</a></td>
                                                <?php } else { ?>
                                                <td width="120"><a href="activate.php<?php echo '?id=' . $id; ?>"
                                                        class="btn btn-success"><i class="icon-check"></i> Activar</a>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                    <button type="submit" name="delete_teacher" class="btn btn-danger"><i
                                            class="icon-trash icon-large"></i> Eliminar Seleccionados</button>
                                </form>
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