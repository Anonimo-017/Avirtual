<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">Agregar Estudiante</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <form id="add_student" method="post">

                    <div class="control-group">

                        <div class="controls">
                            <select name="class_id" class="" required>
                                <option></option>
                                <?php
                                $cys_query = mysqli_query($con, "select * from class order by class_name") or die(mysqli_error($con));
                                while ($cys_row = mysqli_fetch_array($cys_query)) {

                                ?>
                                <option value="<?php echo $cys_row['class_id']; ?>">
                                    <?php echo htmlspecialchars(isset($cys_row['class_name']) ? (string)$cys_row['class_name'] : '', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input name="un" class="input focused" id="focusedInput" type="text" placeholder="ID Number"
                                required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input name="fn" class="input focused" id="focusedInput" type="text" placeholder="Nombres"
                                required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input name="ln" class="input focused" id="focusedInput" type="text" placeholder="Apellidos"
                                required>
                        </div>
                    </div>


                    <div class="control-group">
                        <div class="controls">
                            <button name="save" class="btn btn-info"><i class="icon-plus-sign icon-large"></i></button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>

<script>
jQuery(document).ready(function($) {
    $("#add_student").submit(function(e) {
        e.preventDefault();
        var _this = $(e.target);
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "save_student.php",
            data: formData,
            success: function(html) {
                $.jGrowl("Student Successfully  Added", {
                    header: 'Estudiantes agrgados'
                });
                $('#studentTableDiv').load('student_table.php', function(response) {
                    $("#studentTableDiv").html(response);
                    $('#example').dataTable({
                        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                        "sPaginationType": "bootstrap",
                        "oLanguage": {
                            "sLengthMenu": "_MENU_ records per page"
                        }
                    });
                    $(_this).find(":input").val('');
                    $(_this).find('select option').prop('selected', false);
                    $(_this).find('select option:first').prop('selected', true);
                });
            }
        });
    });
});
</script>