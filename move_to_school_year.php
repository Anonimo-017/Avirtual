<!-- Modal para copiar/compartir archivo -->
<div id="user_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Copiar o Compartir Archivo</h3>
    </div>
    <div class="modal-body">
        <center>
            <!-- Opción 1: Copiar a otra clase Año Escolar -->
            <h4>Copiar a otra clase Año Escolar</h4>
            <form method="post">
                <div class="control-group">
                    <label>Seleccionar Año Escolar:</label>
                    <div class="controls">
                        <input type="hidden" name="get_id" value="<?php echo $get_id; ?>">
                        <select name="school_year" class="">
                            <option></option>
                            <?php
							try {
								$query1 = $pdo_conn->prepare("SELECT * FROM teacher_class WHERE class_id = :class_id AND school_year != :school_year");
								$query1->bindParam(':class_id', $class_id, PDO::PARAM_INT);
								$query1->bindParam(':school_year', $school_year);
								$query1->execute();
								while ($row = $query1->fetch(PDO::FETCH_ASSOC)) {
							?>
                            <option><?php echo htmlspecialchars($row['school_year']); ?></option>
                            <input type="hidden" name="teacher_class_id"
                                value="<?php echo intval($row['teacher_class_id']); ?>">
                            <?php }
							} catch (PDOException $e) {
								echo "Error al obtener los años escolares: " . $e->getMessage();
							} ?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <button name="copy_to_class" class="btn btn-danger"><i class="icon-copy"></i> Copiar a
                            Clase</button>
                    </div>
                </div>
            </form>

            <hr>

            <!-- Opción 2: Copiar a Backpack -->
            <h4>Copiar a Backpack</h4>
            <form method="post">
                <div class="control-group">
                    <div class="controls">
                        <button name="copy_to_backpack" class="btn btn-info"><i class="icon-copy"></i> Copiar a
                            Backpack</button>
                    </div>
                </div>
            </form>

            <hr>

            <!-- Opción 3: Compartir con otro profesor -->
            <h4>Compartir con otro profesor</h4>
            <form method="post">
                <div class="control-group">
                    <label>Seleccionar Profesor:</label>
                    <div class="controls">
                        <select name="teacher_id1" class="" required>
                            <option></option>
                            <?php
							try {
								$query = $pdo_conn->prepare("SELECT * FROM teacher ORDER BY firstname");
								$query->execute();
								while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
							?>

                            <option value="<?php echo intval($row['teacher_id']); ?>">
                                <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?> </option>

                            <?php }
							} catch (PDOException $e) {
								echo "Error al obtener los profesores: " . $e->getMessage();
							} ?>
                        </select>

                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <button name="share" class="btn btn-success"><i class="icon-copy"></i> Compartir</button>
                    </div>
                </div>
            </form>
        </center>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i>
            Cerrar</button>

    </div>
</div>