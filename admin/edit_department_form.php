<div class="row-fluid">
    <a href="department.php" class="btn btn-info"><i class="icon-plus-sign icon-large"></i> Add Department</a>
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">Editar departamentoss</div>
        </div>
        <?php
        include('dbcon.php');

        $get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $query = mysqli_query($con, "SELECT * FROM department WHERE department_id = '$get_id'") or die(mysqli_error($con));
        $row = mysqli_fetch_array($query);
        ?>
        <div class="block-content collapse in">
            <div class="span12">
                <form method="post">
                    <div class="control-group">
                        <div class="controls">
                            <p>Persona Encargada</p>
                            <input class="input focused"
                                value="<?php echo htmlspecialchars($row['dean'], ENT_QUOTES, 'UTF-8'); ?>"
                                id="focusedInput" name="dean" type="text" placeholder="Persona Encargada">
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <p> Departamento</p>
                            <input class="input focused"
                                value="<?php echo htmlspecialchars($row['department_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                id="focusedInput" name="department_name" type="text" placeholder="Departamento-Sede">

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
</div>

<?php
if (isset($_POST['update'])) {
    $dean = mysqli_real_escape_string($con, $_POST['dean']);
    $department_name = mysqli_real_escape_string($con, $_POST['department_name']);

    mysqli_query($con, "UPDATE department SET department_name = '$department_name', dean = '$dean' WHERE department_id = '$get_id'") or die(mysqli_error($con));
}
?>