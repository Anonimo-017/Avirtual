<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>

<body id="class_div">
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('teacher_add_announcement_sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <ul class="breadcrumb">
                        <?php

                        $session_id = mysqli_real_escape_string($con, $session_id);
                        $school_year_query = mysqli_query($con, "SELECT * FROM school_year ORDER BY school_year DESC") or die(mysqli_error($con));
                        $school_year_query_row = mysqli_fetch_array($school_year_query);
                        if ($school_year_query_row) {
                            $school_year = htmlspecialchars($school_year_query_row['school_year'], ENT_QUOTES, 'UTF-8');
                        } else {
                            $school_year = "N/A";
                        }
                        ?>
                        <li><a href="#"><b>Mi Clase</b></a><span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo $school_year; ?></a></li>
                    </ul>

                    <div class="block">
                        <div class="navbar navbar-inner block-header">
                            <div id="count_class" class="muted pull-right">

                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span8">
                                <form class="" id="add_downloadble" method="post">
                                    <div class="control-group">

                                        <div class="controls">


                                            <textarea name="content" id="ckeditor_full"></textarea>
                                        </div>
                                    </div>



                                    <script>
                                    jQuery(document).ready(function($) {
                                        $("#add_downloadble").submit(function(e) {
                                            e.preventDefault();
                                            var content = CKEDITOR.instances.ckeditor_full.getData();

                                            var sanitizedContent = $("<div/>").text(content).html();

                                            var formData = $(this).serialize() + '&content=' +
                                                encodeURIComponent(sanitizedContent);
                                            $.ajax({
                                                type: "POST",
                                                url: "add_announcement_save.php",
                                                data: formData,
                                                success: function(html) {
                                                    $.jGrowl(
                                                        "Announcement Successfully  Added", {
                                                            header: 'Announcement Added'
                                                        });
                                                    window.location =
                                                        'add_announcement.php';
                                                },
                                                error: function(xhr, status, error) {
                                                    $.jGrowl("Error adding announcement: " +
                                                        error, {
                                                            header: 'Error'
                                                        });
                                                }

                                            });
                                        });
                                    });
                                    </script>


                            </div>
                            <div class="span4">

                                <div class="alert alert-info">Seleccione la Clase a la cual desea agregar el archivo.
                                </div>

                                <div class="pull-left">
                                    Seleccionar Todo <input type="checkbox" name="selectAll" id="checkAll" />
                                    <script>
                                    $("#checkAll").click(function() {
                                        $('input:checkbox').not(this).prop('checked', this.checked);
                                    });
                                    </script>
                                </div>
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="">

                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre de Clase</th>
                                            <th>Codigo Asignatura</th>
                                        </tr>

                                    </thead>
                                    <tbody>

                                        <?php
                                        $session_id = mysqli_real_escape_string($con, $session_id);
                                        $school_year = mysqli_real_escape_string($con, $school_year);

                                        $query = mysqli_query($con, "SELECT * FROM teacher_class
                                        LEFT JOIN class ON class.class_id = teacher_class.class_id
                                        LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
                                        WHERE teacher_id = '$session_id' AND school_year = '$school_year'") or die(mysqli_error($con));

                                        while ($row = mysqli_fetch_array($query)) {
                                            $id = intval($row['teacher_class_id']);
                                            $class_name = htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8');
                                            $subject_code = htmlspecialchars($row['subject_code'], ENT_QUOTES, 'UTF-8');

                                        ?>
                                        <tr id="del<?php echo $id; ?>">
                                            <td width="30">
                                                <input id="" class="" name="selector[]" type="checkbox"
                                                    value="<?php echo $id; ?>">
                                            </td>
                                            <td><?php echo $class_name; ?></td>
                                            <td><?php echo $subject_code; ?></td>
                                        </tr>

                                        <?php } ?>



                                    </tbody>
                                </table>


                            </div>
                            <div class="span10">
                                <hr>
                                <center>
                                    <div class="control-group">
                                        <div class="controls">
                                            <button name="Upload" type="submit" value="Upload"
                                                class="btn btn-success" /><i
                                                class="icon-check"></i>&nbsp;Publicar</button>
                                        </div>
                                    </div>
                                </center>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <?php include('teacher_right_sidebar.php') ?>

        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>