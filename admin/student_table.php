<?php include('dbcon.php'); ?>
<form action="delete_student.php" method="post">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">

        <a data-toggle="modal" href="#student_delete" id="delete" class="btn btn-danger" name="delete_students"><i
                class="icon-trash icon-large"></i></a>

        <?php include('modal_delete.php'); ?>
        <thead>
            <tr>
                <th></th>

                <th>Nombre</th>
                <th>Matricula</th>
                <th>Cuatrimestre</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php
            $query = mysqli_query($con, "select * from student LEFT JOIN class ON student.class_id = class.class_id ORDER BY student.student_id DESC") or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($query)) {
                $id = intval($row['student_id']);
            ?>

            <tr>
                <td width="30">
                    <input id="optionsCheckbox" class="uniform_on" name="selector[]" type="checkbox"
                        value="<?php echo $id; ?>">
                </td>

                <td><?php echo htmlspecialchars(($row['firstname'] ?? '') . " " . ($row['lastname'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td><?php echo htmlspecialchars($row['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                <td width="100"><?php echo htmlspecialchars($row['class_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                <td width="30"><a href="edit_student.php?id=<?php echo $id; ?>" class="btn btn-success"><i
                            class="icon-pencil"></i> </a></td>

            </tr>
            <?php } ?>

        </tbody>
    </table>
    <button type="submit" name="delete_student" class="btn btn-danger"><i class="icon-trash icon-large"></i> Eliminar
        Seleccionados</button>
</form>