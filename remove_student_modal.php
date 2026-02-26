<!-- Modal -->
<div id="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" class="modal hide fade" tabindex="-1" role="dialog"
    aria-labelledby="removeStudentModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="removeStudentModalLabel">Eliminar estudiante de la clase</h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            ¿Seguro que quieres eliminar el/los estudiante(s) selecionados?
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i>
            Cerrar</button>
        <button name="remove" class="btn btn-danger remove"
            id="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>"><i class="icon-check icon-large"></i>
            Si</button>
    </div>
</div>