<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body style="padding-top: 70px;">
    <?php include('navbar.php') ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('sidebar_dashboard.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid"></div>

                <div class="row-fluid">
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner blockheader">
                            <div class="muted pull-left">ESTADISTICAS</div>
                            <div class="pull-right">
                                <a href="generate_all_reports.php" class="btn btn-info"><i class="icon-print"></i>
                                    Generar Reporte general PDF</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">

                                <?php
                                $query_teacher = mysqli_query($con, "select * from teacher") or die(mysqli_error($con));
                                $count_teacher = mysqli_num_rows($query_teacher);
                                ?>


                                <div class="span3">
                                    <div class="chart" data-percent="<?php echo $count_teacher; ?>">
                                        <?php echo $count_teacher ?></div>
                                    <div class="chart-bottom-heading"><strong>Profesores</strong>

                                    </div>
                                </div>



                                <?php
                                $query_student = mysqli_query($con, "select * from student") or die(mysqli_error($con));
                                $count_student = mysqli_num_rows($query_student);
                                ?>

                                <div class="span3">
                                    <div class="chart" data-percent="<?php echo $count_student ?>">
                                        <?php echo $count_student ?></div>
                                    <div class="chart-bottom-heading"><strong>Estudiantes</strong>

                                    </div>
                                </div>






                                <?php
                                $query_class = mysqli_query($con, "select * from class") or die(mysqli_error($con));
                                $count_class = mysqli_num_rows($query_class);
                                ?>

                                <div class="span3">
                                    <div class="chart" data-percent="<?php echo $count_class; ?>">
                                        <?php echo $count_class; ?></div>
                                    <div class="chart-bottom-heading"><strong>Clases</strong>

                                    </div>
                                </div>


                                <?php
                                $query_subject = mysqli_query($con, "select * from subject") or die(mysqli_error($con));
                                $count_subject = mysqli_num_rows($query_subject);
                                ?>

                                <div class="span3">
                                    <div class="chart" data-percent="<?php echo $count_subject; ?>">
                                        <?php echo $count_subject; ?></div>
                                    <div class="chart-bottom-heading"><strong>Carreras</strong>

                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- /block -->

                    </div>
                </div>




            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>