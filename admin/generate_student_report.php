<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('vendors/TCPDF/TCPDF-main/tcpdf.php');
include('dbcon.php');

$organization = array(
    'name' => 'Universida Tecnologica de la Tarahumara',
    'address' => 'Carretera Guachochi - Yoquivo, KM. 1.5, Colonia Turuseachi, 33184 Guachochi, Chih.',
    'logo' => 'path/to/your/logo.png',
    'header_image' => __DIR__ . '/images/header.png',
    'greca_image' => __DIR__ . '/images/grecas.png'
);

function getStudentData($con)
{
    $query = "SELECT * FROM student LEFT JOIN class ON student.class_id = class.class_id ORDER BY student.student_id DESC";
    $result = mysqli_query($con, $query);

    $data = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        error_log("Database query failed: " . mysqli_error($con));
        return [];
    }
    return $data;
}

$studentData = getStudentData($con);

class MYPDF extends TCPDF
{
    protected $header_image;
    protected $greca_image;
    protected $organization;

    public function __construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $organization)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        $this->organization = $organization;

        if (file_exists($organization['header_image'])) {
            $this->header_image = $organization['header_image'];
        } else {
            $this->header_image = '';
        }

        if (file_exists($organization['greca_image'])) {
            $this->greca_image = $organization['greca_image'];
        } else {
            $this->greca_image = '';
        }
    }

    public function setHeaderImage($image)
    {
        $this->header_image = $image;
    }

    public function setGrecaImage($image)
    {
        $this->greca_image = $image;
    }

    public function Header()
    {
        if (file_exists($this->header_image)) {
            $this->Image($this->header_image, 0, 0, 210, 30, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        if (file_exists($this->greca_image)) {
            $this->Image($this->greca_image, 190, 0, 20, 297, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
    }

    public function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 5, htmlspecialchars($this->organization['address'], ENT_QUOTES, 'UTF-8'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln(5);
        $this->Cell(0, 5, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, $organization);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($organization['name']);
$pdf->SetTitle('Reporte de Estudiantes');
$pdf->SetSubject('Student Report');
$pdf->SetKeywords('PDF, report, student');

$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$marginLeft = 15;
$marginRight = 15;
$marginTop = 40;
$marginBottom = 15;

$pdf->SetMargins($marginLeft, $marginTop, $marginRight);
$pdf->SetHeaderMargin($marginTop - 10);
$pdf->SetFooterMargin($marginBottom);

$pdf->SetAutoPageBreak(TRUE, $marginBottom);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->setFontSubsetting(true);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(0, 0, 128);

$pdf->SetY(45);

$pdf->Cell(0, 10, 'Reporte de Estudiantes', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(60);

$pdf->Write(0, 'Organización: ' . htmlspecialchars($organization['name'], ENT_QUOTES, 'UTF-8') . "\n");
$pdf->Write(0, 'Fecha de generación: ' . date('Y-m-d H:i:s') . "\n\n");

$studentHeaders = ['Nombre', 'Matricula', 'Cuatrimestre'];
$studentDataFormatted = array_map(function ($row) {
    return [
        $row['firstname'] . ' ' . $row['lastname'],
        $row['username'],
        $row['class_name']
    ];
}, $studentData);

function generateTable($pdf, $data, $headers)
{
    $marginLeft = 15;

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetX($marginLeft);

    $num_headers = count($headers);
    $col_width = 170 / $num_headers;

    foreach ($headers as $header) {
        $pdf->Cell($col_width, 7, $header, 1, 0, 'C', 1);
    }
    $pdf->Ln();

    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTextColor(0, 0, 0);

    foreach ($data as $row) {
        $pdf->SetX($marginLeft);
        $i = 0;
        foreach ($row as $value) {
            $safeValue = ($value === null) ? '' : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $pdf->Cell($col_width, 6, $safeValue, 1, ($i == $num_headers - 1) ? 1 : 0, 'L');
            $i++;
        }
    }
    $pdf->Ln(5);
}

generateTable($pdf, $studentDataFormatted, $studentHeaders);

$pdf->Output('reporte_estudiantes.pdf', 'D');