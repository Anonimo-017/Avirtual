<form id="signin_student" class="form-signin" method="post">
    <h4 class="form-signin-heading"><i class="icon-plus-sign"></i> Agregar Evento</h4>
    <input type="text" class="input-block-level datepicker" name="date_start" id="date01" placeholder="Fecha Inicio"
        required />
    <input type="text" class="input-block-level datepicker" name="date_end" id="date01" placeholder="Fecha Fin"
        required />
    <input type="text" class="input-block-level" id="username" name="title" placeholder="Titulo Evento" required />
    <button id="signin" name="add" class="btn btn-info" type="submit"><i class="icon-save"></i> Guardar</button>
</form>
<?php
if (isset($_POST['add'])) {
	$date_start = $_POST['date_start'];
	$date_end = $_POST['date_end'];
	$title = $_POST['title'];

	try {
		$sql = "INSERT INTO event (date_end, date_start, event_title, teacher_class_id) VALUES (:date_end, :date_start, :title, :teacher_class_id)";
		$stmt = $pdo_conn->prepare($sql);
		$stmt->bindParam(':date_end', $date_end);
		$stmt->bindParam(':date_start', $date_start);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
		$stmt->execute();
?>
<script>
window.location = "class_calendar.php<?php echo '?id=' . $get_id; ?>";
</script>
<?php
	} catch (PDOException $e) {
		echo "Error al agregar el evento: " . $e->getMessage();
	}
}
?>

<table cellpadding="0" cellspacing="0" border="0" class="table" id="">

    <?php include('move_to_school_year.php'); ?>
    <thead>
        <tr>
            <th>Evento</th>
            <th>Fecha</th>
            <th></th>

        </tr>

    </thead>
    <tbody>


        <?php
		try {
			$event_query = $pdo_conn->prepare("SELECT * FROM event WHERE teacher_class_id = :teacher_class_id");
			$event_query->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
			$event_query->execute();
			while ($event_row = $event_query->fetch(PDO::FETCH_ASSOC)) {
				$id  = intval($event_row['event_id']);
		?>
        <tr id="del<?php echo $id; ?>">

            <td><?php echo htmlspecialchars($event_row['event_title']); ?></td>
            <td><?php echo htmlspecialchars($event_row['date_start']); ?>
                <br>
                <?php echo htmlspecialchars($event_row['date_end']); ?>
            </td>
            <td width="40">
                <form method="post" action="delete_class_event.php">
                    <input type="hidden" name="get_id" value="<?php echo $get_id; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button class="btn btn-danger" name="delete_event"><i class="icon-remove icon-large"></i></button>
                </form>
            </td>


        </tr>

        <?php }
		} catch (PDOException $e) {
			echo "Error al obtener los eventos: " . $e->getMessage();
		} ?>


    </tbody>
</table>