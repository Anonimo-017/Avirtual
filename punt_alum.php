<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>

<?php
include('navbar_teacher.php');
require 'dbcon.php';

$where_clauses = [];
if (!empty($_GET['titulo'])) {
    $titulo = $con->real_escape_string($_GET['titulo']);
    $where_clauses[] = "pq.quiz_title LIKE '%$titulo%'";
}
$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

$query = "SELECT s.firstname, s.lastname, pq.quiz_title, pq.grade, pq.date_taken
          FROM punt_student_quiz pq
          JOIN student s ON pq.student_id = s.student_id
          $where_sql";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <style>
    .container-flex {
        display: flex;
        align-items: flex-start;
        margin-top: 20px;
    }

    .left-side {
        width: 300px;
    }

    .right-side {
        flex: 1;
    }
    </style>
</head>

<body>

    <div class="container-flex">
        <?php include('punt_alum_sidebar.php'); ?>

        <div class="left-side">
        </div>
        <div class="right-side">
            <h2 class="mb-3">Calificaciones</h2>
            <form method="GET" action="">
                <input type="text" name="titulo" placeholder="Título del quiz"
                    value="<?php echo isset($_GET['titulo']) ? htmlspecialchars($_GET['titulo']) : ''; ?>">
                <button type="submit" class="btn btn-secondary">Filtrar</button>
            </form>
            <?php
            if (!empty($_GET['titulo'])) {
                echo "<p>Filtro activo: Título contiene '" . htmlspecialchars($_GET['titulo']) . "'</p>";
            }
            ?>

            <a href="descargar_calificaciones.php?titulo=<?php echo isset($_GET['titulo']) ? urlencode($_GET['titulo']) : ''; ?>"
                target="_blank" class="btn btn-primary mb-3">Descargar Calificaciones</a>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre del alumno</th>
                            <th>Título del quiz</th>
                            <th>Calificación</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $valor = $row['grade'];
                            $partes = explode(' out of ', $valor);
                            if (count($partes) == 2 && is_numeric($partes[0]) && is_numeric($partes[1])) {
                                $aciertos = floatval($partes[0]);
                                $total = floatval($partes[1]);
                                $percentage = ($aciertos / $total) * 100;
                                $percentage = round($percentage, 2);
                            } else {
                                $percentage = 'N/A';
                            }

                            echo "<tr>
                          <td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>
                          <td>" . htmlspecialchars($row['quiz_title']) . "</td>
                          <td>" . htmlspecialchars($percentage) . "%</td>
                          <td>" . htmlspecialchars($row['date_taken']) . "</td>
                        </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>