<!-- Modal -->
<div id="<?php echo $id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Eliminar asignatura</h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            Seguro que quieres eliminar esta asignatura
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i>
            Cerrar</button>
        <a href="delete_assignment.php?id=<?php echo $id; ?>&get_id=<?php echo $get_id; ?>" class="btn btn-danger"><i
                class="icon-check icon-large"></i> Si</a>
    </div>
</div>