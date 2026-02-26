<div class="row-fluid">
   <a href="admin_user.php" class="btn btn-info"><i class="icon-plus-sign icon-large"></i> Añadir Usuario</a>
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Editar informacion</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
								<?php
								$query = mysqli_query($con, "select * from users where user_id = '$get_id'")or die(mysqli_error($con));
								$row = mysqli_fetch_array($query);
								?>
								<form method="post">
										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo htmlspecialchars($row['firstname']); ?>" name="firstname" id="focusedInput" type="text" placeholder = "Nombres" required>
                                          </div>
                                        </div>
										
										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo htmlspecialchars($row['lastname']); ?>"  name="lastname" id="focusedInput" type="text" placeholder = "Apellidos" required>
                                          </div>
                                        </div>
										
											<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo htmlspecialchars($row['username']); ?>"  name="username" id="focusedInput" type="text" placeholder = "Usuario" required>
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
if (isset($_POST['update'])){

$firstname = mysqli_real_escape_string($con, $_POST['firstname']);
$lastname = mysqli_real_escape_string($con, $_POST['lastname']);
$username = mysqli_real_escape_string($con, $_POST['username']);


mysqli_query($con, "update users set username = '$username'  , firstname = '$firstname' , lastname = '$lastname' where user_id = '$get_id' ")or die(mysqli_error($con));

mysqli_query($con, "insert into activity_log (date,username,action) values(NOW(),'$user_username','Edit User $username')")or die(mysqli_error($con));
?>
<script>
  window.location = "admin_user.php"; 
</script>
<?php
}
?>