<?php

require_once('admin/vendors/TCPDF/TCPDF-main/tcpdf.php');

include('dbcon.php');

$tituloFiltro = isset($_GET['titulo']) ? $con->real_escape_string($_GET['titulo']) : '';

$whereSql = '';
if (!empty($tituloFiltro)) {
    $whereSql = "WHERE pq.quiz_title LIKE '%$tituloFiltro%'";
}

$query = "SELECT s.firstname, s.lastname, pq.quiz_title, pq.grade, pq.date_taken
          FROM punt_student_quiz pq
          JOIN student s ON pq.student_id = s.student_id
          $whereSql";

$result = $con->query($query);

$organization = [
    'name' => 'Universidad Tecnologica de la Tarahumara',
    'address' => 'Carretera Guachochi - Yoquivo, KM. 1.5, Colonia Turuseachi, 33184 Guachochi, Chih.',
    'logo' => 'admin/images/logo-utt.png',
    'header_image' =>  'admin/images/header.png',
    'greca_image' =>  'admin/images/grecas.png'
];

class MYPDF extends TCPDF
{
    protected $organization;
    public function __construct($organization)
    {
        parent::__construct();
        $this->organization = $organization;
    }
    public function Header()
    {
        if (file_exists($this->organization['header_image'])) {
            $this->Image($this->organization['header_image'], 10, 10, 190);
        }
        $this->SetY(30);
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'Reporte de Calificaciones', 0, 1, 'C');
    }
    public function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, $this->organization['address'], 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new MYPDF($organization);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($organization['name']);
$pdf->SetTitle('Reporte de Calificaciones');
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
$pdf->SetMargins(15, 50, 15);
$pdf->SetAutoPageBreak(true, 30);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Calificaciones de los Alumnos', 0, 1, 'C');
$pdf->Ln(5);

$headers = ['Nombre del alumno', 'Título del quiz', 'Calificación', 'Fecha'];
$dataRows = [];
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
    $dataRows[] = [
        $row['firstname'] . ' ' . $row['lastname'],
        $row['quiz_title'],
        $percentage . '%',
        $row['date_taken']
    ];
}

$colWidths = [60, 50, 30, 40];

$pdf->SetFillColor(200, 200, 200);
$pdf->SetFont('helvetica', 'B', 10);
for ($i = 0; $i < count($headers); $i++) {
    $pdf->Cell($colWidths[$i], 10, $headers[$i], 1, 0, 'C', 1);
}
$pdf->Ln();

$pdf->SetFont('helvetica', '', 9);
foreach ($dataRows as $row) {
    for ($i = 0; $i < count($row); $i++) {
        $pdf->Cell($colWidths[$i], 8, $row[$i], 1, 0, 'L');
    }
    $pdf->Ln();
}

$pdf->Output('calificaciones.pdf', 'D');