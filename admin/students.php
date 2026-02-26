<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('student_sidebar.php'); ?>
            <div class="span3" id="adduser">
                <?php include('add_students.php'); ?>
            </div>
            <div class="span6" id="">
                <div class="row-fluid">
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Lista Estudiantes</div>
                            <div class="pull-right">
                                <a href="generate_student_report.php" class="btn btn-info"><i class="icon-print"></i>
                                    Generar Reporte PDF</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12" id="studentTableDiv">
                                <?php include('student_table.php'); ?>
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