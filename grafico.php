<?php
include('dbcon.php');

$tituloFiltro = isset($_GET['titulo']) ? $_GET['titulo'] : '';

$whereSql = '';
if (!empty($tituloFiltro)) {
    $tituloFiltroEsc = $con->real_escape_string($tituloFiltro);
    $whereSql = "WHERE pq.quiz_title LIKE '%$tituloFiltroEsc%'";
}

$query = "SELECT s.firstname, s.lastname, pq.quiz_title, pq.grade, pq.date_taken
          FROM punt_student_quiz pq
          JOIN student s ON pq.student_id = s.student_id
          $whereSql";

$result = $con->query($query);

$alumnos = [];
foreach ($result as $row) {
    $nombre = $row['firstname'] . ' ' . $row['lastname'];
    $calificacion = 0;

    $valor = $row['grade'];
    $partes = explode(' out of ', $valor);
    if (count($partes) == 2 && is_numeric($partes[0]) && is_numeric($partes[1])) {
        $aciertos = floatval($partes[0]);
        $total = floatval($partes[1]);
        $calificacion = ($aciertos / $total) * 100;
    }
    if (!isset($alumnos[$nombre])) {
        $alumnos[$nombre] = ['total' => 0, 'count' => 0];
    }
    $alumnos[$nombre]['total'] += $calificacion;
    $alumnos[$nombre]['count']++;
}

$labels = [];
$promedios = [];
foreach ($alumnos as $nombre => $datos) {
    $promedio = $datos['total'] / $datos['count'];
    $labels[] = $nombre;
    $promedios[] = round($promedio, 2);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Gráfico de Calificaciones</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h2>Calificaciones Promedio por Alumno</h2>
    <p>Filtro aplicado: <?php echo htmlspecialchars($tituloFiltro); ?></p>
    <canvas id="myChart" width="700" height="400"></canvas>

    <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const labels = <?php echo json_encode($labels); ?>;
    const data = {
        labels: labels,
        datasets: [{
            label: 'Promedio de calificación (%)',
            data: <?php echo json_encode($promedios); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };
    const config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    };
    const myChart = new Chart(ctx, config);
    </script>
</body>

</html>