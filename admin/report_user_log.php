<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('vendors/TCPDF/TCPDF-main/tcpdf.php');
include('dbcon.php');

$organization = array(
    'name' => 'Universidad Tecnologica de la Tarahumara',
    'address' => 'Carretera Guachochi - Yoquivo, KM. 1.5, Colonia Turuseachi, 33184 Guachochi, Chih.',
    'logo' => 'images/logo-utt.png',
    'header_image' => __DIR__ . '/images/header.png',
    'greca_image' => __DIR__ . '/images/grecas.png'
);

function getUserLogData($con, $start_date = '', $end_date = '', $username = '')
{
    $query = "SELECT * FROM user_log WHERE 1=1";

    if (!empty($start_date)) {
        $query .= " AND login_date >= '" . mysqli_real_escape_string($con, $start_date) . "'";
    }
    if (!empty($end_date)) {
        $query .= " AND login_date <= '" . mysqli_real_escape_string($con, $end_date) . "'";
    }
    if (!empty($username)) {
        $query .= " AND username LIKE '%" . mysqli_real_escape_string($con, $username) . "%'";
    }

    $query .= " ORDER BY user_log_id DESC";

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

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';

$userLogData = getUserLogData($con, $start_date, $end_date, $username);

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
        $this->Cell(0, 5, 'Pag ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, $organization);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($organization['name']);
$pdf->SetTitle('Reporte de Historial de Usuarios');
$pdf->SetSubject('User Log Report');
$pdf->SetKeywords('PDF, report, user log');
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$marginLeft = 15;
$marginRight = 15;
$marginTop = 20;
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

$pdf->Cell(0, 10, 'Reporte de Historial de Usuarios', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(60);

$pdf->Write(0, 'Organización: ' . htmlspecialchars($organization['name'], ENT_QUOTES, 'UTF-8') . "\n");
$pdf->Write(0, 'Fecha de generación: ' . date('Y-m-d H:i:s') . "\n\n");

if (!empty($start_date) || !empty($end_date) || !empty($username)) {
    $pdf->Write(0, "Filtros Aplicados:\n");
    if (!empty($start_date)) {
        $pdf->Write(0, 'Fecha de Inicio: ' . htmlspecialchars($start_date, ENT_QUOTES, 'UTF-8') . "\n");
    }
    if (!empty($end_date)) {
        $pdf->Write(0, 'Fecha de Fin: ' . htmlspecialchars($end_date, ENT_QUOTES, 'UTF-8') . "\n");
    }
    if (!empty($username)) {
        $pdf->Write(0, 'Usuario: ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "\n");
    }
    $pdf->Ln(2);
}

$userLogHeaders = ['Fecha Inicio de Sesión', 'Fecha Cierre de Sesión', 'Usuario'];
$userLogDataFormatted = array_map(function ($row) {
    return [
        $row['login_date'],
        $row['logout_date'],
        $row['username']
    ];
}, $userLogData);

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

generateTable($pdf, $userLogDataFormatted, $userLogHeaders);
$pdf->Output('reporte_historial_usuarios.pdf', 'D');