<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php $get_id = isset($_GET['id']) ? intval($_GET['id']) : 0; ?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('downloadable_link_student.php'); ?>
            <div class="span6" id="content">
                <div class="row-fluid">

                    <?php $class_query = mysqli_query($con, "select * from teacher_class
										LEFT JOIN class ON class.class_id = teacher_class.class_id
										LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
										where teacher_class_id = '$get_id'") or die(mysqli_error($con));
                    $class_row = mysqli_fetch_array($class_query);
                    $class_id = $class_row['class_id'];
                    $school_year = $class_row['school_year'];
                    ?>

                    <ul class="breadcrumb">
                        <li><a
                                href="#"><?php echo htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a
                                href="#"><?php echo htmlspecialchars($class_row['subject_code'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#">Año Escolar:
                                <?php echo htmlspecialchars($class_row['school_year'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Material Descargable</b></a></li>
                    </ul>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <?php $query = mysqli_query($con, "select * FROM files where class_id = '$get_id' order by fdatein DESC ") or die(mysqli_error($con));
                            $count = mysqli_num_rows($query);
                            ?>
                            <div id="" class="muted pull-right"><span
                                    class="badge badge-info"><?php echo $count; ?></span>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <div class="pull-right">
                                    Seleccionar Todo <input type="checkbox" name="selectAll" id="checkAll" />
                                    <script>
                                    $("#checkAll").click(function() {
                                        $('input:checkbox').not(this).prop('checked', this.checked);
                                    });
                                    </script>
                                </div>
                                <?php
                                $query = mysqli_query($con, "select * FROM files where class_id = '$get_id' order by fdatein DESC ") or die(mysqli_error($con));
                                $count = mysqli_num_rows($query);
                                if ($count == '0') { ?>
                                <div class="alert alert-info"><i class="icon-info-sign"></i> No hay material
                                    descargable.
                                </div>
                                <?php
                                } else {
                                ?>

                                <form action="copy_file_student.php" method="post">


                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="">

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Fecha Subida</th>
                                                <th>Nombre de Archivo</th>
                                                <th>Descripcion</th>
                                                <th>Subido por</th>
                                                <th>Descargar</th>

                                            </tr>

                                        </thead>
                                        <tbody>

                                            <?php
                                                $query = mysqli_query($con, "select * FROM files where class_id = '$get_id' order by fdatein DESC ") or die(mysqli_error($con));
                                                while ($row = mysqli_fetch_array($query)) {
                                                    $id  = intval($row['file_id']);
                                                    $floc = htmlspecialchars($row['floc'], ENT_QUOTES, 'UTF-8');
                                                ?>
                                            <tr>
                                                <td width="30">
                                                    <input id="" class="" name="selector[]" type="checkbox"
                                                        value="<?php echo $id; ?>">
                                                </td>

                                                <td><?php echo htmlspecialchars($row['fdatein'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['fdesc'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['uploaded_by'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td width="30">
                                                    <a data-placement="bottom" title="Download"
                                                        id="<?php echo $id; ?>Download" href="<?php echo $floc; ?>"
                                                        download="<?php echo basename($floc); ?>">
                                                        <i class="icon-download icon-large"></i>
                                                    </a>
                                                    <script type="text/javascript">
                                                    $(document).ready(function() {
                                                        $('#<?php echo $id; ?>Download').tooltip('show');
                                                        $('#<?php echo $id; ?>Download').tooltip('hide');
                                                    });
                                                    </script>

                                                    <a data-placement="bottom" title="View" id="<?php echo $id; ?>View"
                                                        href="<?php echo $floc; ?>" target="_blank">
                                                        <i class="icon-eye-open icon-large"></i>
                                                    </a>
                                                    <script type="text/javascript">
                                                    $(document).ready(function() {
                                                        $('#<?php echo $id; ?>View').tooltip('show');
                                                        $('#<?php echo $id; ?>View').tooltip('hide');
                                                    });
                                                    </script>
                                                </td>
                                            </tr>

                                            <?php } ?>


                                        </tbody>
                                    </table>
                                </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- /block -->
                </div>
            </div>
            <?php include('downloadable_sidebar_student.php'); ?>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>