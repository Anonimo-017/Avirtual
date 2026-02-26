<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Cambiar Imagen de Perfil</h4>
            </div>
            <div class="modal-body">
                <form action="upload_profile_picture_teacher.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Seleccionar Imagen:</label>
                        <input type="file" name="image" id="image" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" name="upload" class="btn btn-primary">Subir</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->