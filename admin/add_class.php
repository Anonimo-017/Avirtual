     <div class="row-fluid">
         <div class="block">
             <div class="navbar navbar-inner block-header">
                 <div class="muted pull-left">Añadir Carrera</div>
             </div>
             <div class="block-content collapse in">
                 <div class="span12">
                     <form method="post">
                         <div class="control-group">
                             <div class="controls">
                                 <input name="class_name" class="input focused" id="focusedInput" type="text"
                                     placeholder="Nombre de la carrera" required>
                             </div>
                         </div>


                         <div class="control-group">
                             <div class="controls">
                                 <button name="save" class="btn btn-info"><i
                                         class="icon-plus-sign icon-large"></i></button>

                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
     <?php
        if (isset($_POST['save'])) {
            $class_name = mysqli_real_escape_string($con, $_POST['class_name']);

            $query = mysqli_query($con, "select * from class where class_name  =  '$class_name' ") or die(mysqli_error($con));
            $count = mysqli_num_rows($query);

            if ($count > 0) { ?>
     <script>
alert('Date Already Exist');
     </script>
     <?php
            } else {
                mysqli_query($con, "insert into class (class_name) values('$class_name')") or die(mysqli_error($con));
            ?>
     <script>
window.location = "class.php";
     </script>
     <?php
            }
        }
        ?>