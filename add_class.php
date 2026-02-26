<div class="block">
    <div class="navbar navbar-inner block-header">
        <div id="" class="muted pull-left">
            <h4><i class="icon-plus-sign"></i>Añadir Clase</h4>
        </div>
    </div>
    <div class="block-content collapse in">
        <div class="span12">
            <form method="post" id="add_class">
                <div class="control-group">
                    <label>Carrera:</label>
                    <div class="controls">
                        <?php
                        $session_id = mysqli_real_escape_string($con, $session_id);
                        ?>
                        <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                        <select name="class_id" class="" required>
                            <option></option>
                            <?php
                            $query = mysqli_query($con, "select * from class order by class_name") or die(mysqli_error($con));
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <option value="<?php echo intval($row['class_id']); ?>">
                                <?php echo htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <label>clase:</label>
                    <div class="controls">
                        <select name="subject_id" class="" required>
                            <option></option>
                            <?php
                            $query = mysqli_query($con, "select * from subject order by subject_title") or die(mysqli_error($con));
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <option value="<?php echo intval($row['subject_id']); ?>">
                                <?php echo htmlspecialchars($row['subject_title'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <label>Año Escolar:</label>
                    <div class="controls">
                        <select name="school_year" class="span5" required>
                            <option></option>
                            <?php
                            $query = mysqli_query($con, "SELECT * FROM school_year ORDER BY school_year DESC") or die(mysqli_error($con));
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <option value="<?php echo htmlspecialchars($row['school_year'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($row['school_year'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <button name="save" class="btn btn-success"><i class="icon-save"></i> Guardar</button>
                    </div>
                </div>
            </form>

            <script>
            jQuery(document).ready(function($) {
                $("#add_class").submit(function(e) {
                    e.preventDefault();
                    var _this = $(e.target);
                    var formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: "add_class_action.php",
                        data: formData,
                        success: function(html) {
                            if (html == "true") {
                                $.jGrowl("Class Already Exist", {
                                    header: 'Add Class Failed'
                                });
                            } else {
                                $.jGrowl("Classs Successfully  Added", {
                                    header: 'Class Added'
                                });
                                var delay = 500;
                                setTimeout(function() {
                                    window.location = 'dashboard_teacher.php'
                                }, delay);
                            }
                        },
                        error: function(xhr, status, error) {
                            $.jGrowl("Error adding class: " + error, {
                                header: 'Error'
                            });
                        }
                    });
                });
            });
            </script>
        </div>
    </div>
</div>