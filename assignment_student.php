<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($get_id === false || $get_id === null) {
    die("Invalid class ID.");
}

?>

<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('assignment_link_student.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    $class_query = mysqli_prepare($con, "SELECT tc.teacher_class_id, c.class_name, s.subject_code, tc.school_year
                        FROM teacher_class tc
                        LEFT JOIN class c ON c.class_id = tc.class_id
                        LEFT JOIN subject s ON s.subject_id = tc.subject_id
                        WHERE tc.teacher_class_id = ?");

                    if ($class_query) {
                        mysqli_stmt_bind_param($class_query, "i", $get_id);
                        mysqli_stmt_execute($class_query);
                        $class_result = mysqli_stmt_get_result($class_query);
                        $class_row = mysqli_fetch_array($class_result);

                        if ($class_row) {
                            $class_name = htmlspecialchars($class_row['class_name'], ENT_QUOTES, 'UTF-8');
                            $subject_code = htmlspecialchars($class_row['subject_code'], ENT_QUOTES, 'UTF-8');
                            $school_year = htmlspecialchars($class_row['school_year'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo $class_name; ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><?php echo $subject_code; ?></a> <span class="divider">/</span></li>
                        <li><a href="#">Año Escolar: <?php echo $school_year; ?></a> <span class="divider">/</span>
                        </li>
                        <li><a href="#"><b>Asignaturas Subidas</b></a></li>
                    </ul>
                    <?php
                        } else {
                            echo "<div class='alert alert-danger'>Class not found.</div>";
                        }

                        mysqli_stmt_close($class_query);
                    } else {
                        die("Prepare failed: " . mysqli_error($con));
                    }
                    ?>

                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <?php
                            $query = mysqli_prepare($con, "SELECT assignment_id, fdatein, fname, fdesc, floc FROM assignment WHERE class_id = ? ORDER BY fdatein DESC");

                            if ($query) {
                                mysqli_stmt_bind_param($query, "i", $get_id);
                                mysqli_stmt_execute($query);
                                $result = mysqli_stmt_get_result($query);
                                $count = mysqli_num_rows($result);
                            ?>
                            <div id="" class="muted pull-right"><span
                                    class="badge badge-info"><?php echo htmlspecialchars($count, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php
                                if ($count == 0) {
                                    echo '<div class="alert alert-info">No Assignment Currently Uploaded</div>';
                                } else {
                                ?>
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Fecha Subida</th>
                                            <th>Nombre de Archivo</th>
                                            <th>Descripcion</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while ($row = mysqli_fetch_array($result)) {
                                                $assignment_id = htmlspecialchars($row['assignment_id'], ENT_QUOTES, 'UTF-8');
                                                $fdatein = htmlspecialchars($row['fdatein'], ENT_QUOTES, 'UTF-8');
                                                $fname = htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8');
                                                $fdesc = htmlspecialchars($row['fdesc'], ENT_QUOTES, 'UTF-8');
                                                $floc = htmlspecialchars($row['floc'], ENT_QUOTES, 'UTF-8');

                                                echo "<tr>";
                                                echo "<td>" . $fdatein . "</td>";
                                                echo "<td>" . $fname . "</td>";
                                                echo "<td>" . $fdesc . "</td>";
                                                echo "<td width='220'>";

                                                if ($floc != "") {
                                                    $download_url = htmlspecialchars($floc, ENT_QUOTES, 'UTF-8');
                                                    echo "<a data-placement='bottom' title='View' id='" . $assignment_id . "download' class='btn btn-info' href='" . $download_url . "' target='_blank'><i class='icon-eye-open icon-large'> Ver</i></a>";
                                                }

                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                    </tbody>
                                </table>
                                <?php
                                }
                                mysqli_stmt_close($query);
                            } else {
                                die("Prepare failed: " . mysqli_error($con));
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>

</html>