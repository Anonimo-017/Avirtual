<?php include('header.php'); ?>
<?php include('general/session.php'); ?>

<body>
    <?php include('general/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('carrers/carrers_sidebar.php'); ?>

            <div class="span9" id="content">
                <div class="row-fluid">

                    <div class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Agregar Carrera</div>
                        </div>
                        <div class="block-content collapse in">
                            <a href="subjects.php"><i class="icon-arrow-left"></i>Regresar</a>
                            <form class="form-horizontal" method="post">
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Codigo de la carrera</label>
                                    <div class="controls">
                                        <input type="text" name="subject_code" id="inputEmail">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Carrera</label>
                                    <div class="controls">
                                        <input type="text" class="span8" name="title" id="inputPassword" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Cuatrimestres</label>
                                    <div class="controls">
                                        <input type="text" class="span1" name="unit" id="inputPassword" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Unidades</label>
                                    <div class="controls">
                                        <select name="semester">
                                            <option></option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Descripcion</label>
                                    <div class="controls">
                                        <textarea name="description" id="ckeditor_full"></textarea>
                                    </div>
                                </div>



                                <div class="control-group">
                                    <div class="controls">

                                        <button name="save" type="submit" class="btn btn-info"><i class="icon-save"></i>
                                            Guardar</button>
                                    </div>
                                </div>
                            </form>

                            <?php
                            if (isset($_POST['save'])) {
                                $subject_code = mysqli_real_escape_string($con, $_POST['subject_code']);
                                $title = mysqli_real_escape_string($con, $_POST['title']);
                                $unit = mysqli_real_escape_string($con, $_POST['unit']);
                                $description = mysqli_real_escape_string($con, $_POST['description']);
                                $semester = mysqli_real_escape_string($con, $_POST['semester']);


                                $query = mysqli_query($con, "select * from subject where subject_code = '$subject_code' ") or die(mysqli_error($con));
                                $count = mysqli_num_rows($query);

                                if ($count > 0) { ?>
                            <script>
                            alert('Data Already Exist');
                            </script>
                            <?php
                                } else {
                                    mysqli_query($con, "insert into subject (subject_code,subject_title,description,unit,semester) values('$subject_code','$title','$description','$unit','$semester')") or die(mysqli_error($con));


                                    mysqli_query($con, "insert into activity_log (date,username,action) values(NOW(),'$user_username','Agregar carrera $subject_code')") or die(mysqli_error($con));


                                ?>
                            <script>
                            window.location = "carrers.php";
                            </script>
                            <?php
                                }
                            }

                            ?>


                        </div>
                    </div>
                    <!-- /block -->
                </div>
            </div>
        </div>
        <?php include('pie/footer.php'); ?>
    </div>
    <?php include('general/script.php'); ?>
</body>

</html>