<?php include('header.php'); ?>
<?php include('session.php'); ?>
<?php $get_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Sanitize and validate $get_id 
?>

<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('department_sidebar.php'); ?>
            <div class="span3" id="adduser">
                <?php include('edit_department_form.php'); ?>
            </div>
            <div class="span6" id="">
                <div class="row-fluid">
                    <!-- block -->
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Department List</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_department.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <a data-toggle="modal" href="#department_delete" id="delete"
                                            class="btn btn-danger" name=""><i class="icon-trash icon-large"></i></a>
                                        <?php include('modal_delete.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Departmento</th>
                                                <th>Pesrsona a cargo</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
											include('dbcon.php');
											$user_query = mysqli_query($con, "select * from department") or die(mysqli_error($con));
											while ($row = mysqli_fetch_array($user_query)) {
												$id = intval($row['department_id']);
												$department_name = htmlspecialchars($row['department_name'], ENT_QUOTES, 'UTF-8');
												$dean = htmlspecialchars($row['dean'], ENT_QUOTES, 'UTF-8');
											?>

                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]"
                                                        type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo $department_name; ?></td>
                                                <td><?php echo $dean; ?></td>

                                                <td width="30"><a href="edit_department.php?id=<?php echo $id; ?>"
                                                        class="btn btn-success"><i
                                                            class="icon-pencil icon-large"></i></a></td>


                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /block -->
                </div>


            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>