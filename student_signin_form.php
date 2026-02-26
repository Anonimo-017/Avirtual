<?php
include('dbcon.php');
?>

<form id="signin_student" class="form-signin" method="post" action="student_signup.php">
    <h3 class="form-signin-heading">
        <i class="icon-lock"></i> Registrar Estudiante
    </h3>

    <input type="text" class="input-block-level" name="username" placeholder="Nro Identificacion" required>
    <input type="text" class="input-block-level" name="firstname" placeholder="Nombres" required>
    <input type="text" class="input-block-level" name="lastname" placeholder="Apellidos" required>

    <label>Carrera</label>
    <select name="class_id" class="input-block-level span5" required>
        <option value="">Seleccione</option>
        <?php
        $stmt = $pdo_conn->query("SELECT class_id, class_name FROM class ORDER BY class_name");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <option value="<?php echo htmlspecialchars($row['class_id']); ?>">
            <?php echo htmlspecialchars($row['class_name']); ?>
        </option>
        <?php } ?>
    </select>

    <input type="password" class="input-block-level" name="password" placeholder="Contraseña" required>
    <input type="password" class="input-block-level" name="cpassword" placeholder="Repita Contraseña" required>

    <button class="btn btn-info" type="submit">
        <i class="icon-check icon-large"></i> Registrar
    </button>

    <button type="button" class="btn btn-info" onclick="window.location.href='index.php'">
        <i class="icon-arrow-left icon-large"></i> Volver
    </button>
</form>