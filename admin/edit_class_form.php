<div class="row-fluid">
    <a href="class.php" class="btn btn-info"><i class="icon-plus-sign icon-large"></i>Añadir Clase</a>
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">Editar Clase</div>
        </div>
        <?php
        include('dbcon.php');

        $get_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Sanitize $get_id

        $query = mysqli_query($con, "SELECT * FROM class WHERE class_id = '$get_id'") or die(mysqli_error($con));
        $row = mysqli_fetch_array($query);
        ?>
        <div class="block-content collapse in">
            <div class="span12">
                <form method="post">
                    <div class="control-group">
                        <div class="controls">
                            <input name="class_name"
                                value="<?php echo htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                class="input focused" id="focusedInput" type="text" placeholder="Nombre de Clase"
                                required>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <button name="update" class="btn btn-success"><i class="icon-save icon-large"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>
<?php
if (isset($_POST['update'])) {
    $class_name = mysqli_real_escape_string($con, $_POST['class_name']);

    mysqli_query($con, "UPDATE class SET class_name = '$class_name' WHERE class_id = '$get_id'") or die(mysqli_error($con));
?>
<script>
window.location = "class.php";
</script>
<?php
}
?>