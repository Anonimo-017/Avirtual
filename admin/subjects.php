<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('subject_sidebar.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">
                    <a href="add_subject.php" class="btn btn-info"><i class="icon-plus-sign icon-large"></i>
                        Agregar clase
                    </a>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Lista de clases</div>
                            <div class="pull-right">
                                <a href="generate_subject_report.php" class="btn btn-info"><i class="icon-print"></i>
                                    Generar Reporte PDF</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_subject.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <a data-toggle="modal" href="#subject_delete" id="delete" class="btn btn-danger"
                                            name="delete_subjects"><i class="icon-trash icon-large"></i></a>
                                        <?php include('modal_delete.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Codigo de la clase</th>
                                                <th>Nombre de la clase</th>
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