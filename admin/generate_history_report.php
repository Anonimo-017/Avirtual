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

function generateLoginHistoryReport($con, $filterParams, $organization)
{
    class MYPDF extends TCPDF
    {
        protected $header_image;
        protected $greca_image;
        public static $organization;

        public function __construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $organization)
        {
            parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
            self::$organization = $organization;

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
            $this->Cell(0, 5, htmlspecialchars(self::$organization['address'], ENT_QUOTES, 'UTF-8'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $this->Ln(5);
            $this->Cell(0, 5, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, $organization);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Universidad Tecnologica de la Tarahumara');
    $pdf->SetTitle('Reporte de inicio de sesión');
    $pdf->SetSubject('Historial de inicio de sesión');

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

    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(0, 0, 128);

    $pdf->SetY(45);

    $pdf->Cell(0, 10, 'Reporte del Historial de Inicio de Sesión', 0, 1, 'C');

    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetY(60);

    $pdf->Write(0, 'Organización: ' . htmlspecialchars($organization['name'], ENT_QUOTES, 'UTF-8') . "\n");
    $pdf->Write(0, 'Fecha de generación: ' . date('Y-m-d H:i:s') . "\n\n");

    if (isset($filterParams['start_date']) && $filterParams['start_date'] != '') {
        $pdf->Write(0, 'Fecha de inicio: ' . htmlspecialchars($filterParams['start_date'], ENT_QUOTES, 'UTF-8') . "\n");
    }
    if (isset($filterParams['end_date']) && $filterParams['end_date'] != '') {
        $pdf->Write(0, 'Fecha de fin: ' . htmlspecialchars($filterParams['end_date'], ENT_QUOTES, 'UTF-8') . "\n");
    }
    if (isset($filterParams['user_type']) && $filterParams['user_type'] != '') {
        $pdf->Write(0, 'Tipo de usuario: ' . htmlspecialchars(ucfirst($filterParams['user_type']), ENT_QUOTES, 'UTF-8') . "\n");
    }
    if (isset($filterParams['search_username']) && $filterParams['search_username'] != '') {
        $pdf->Write(0, 'Buscar usuario: ' . htmlspecialchars($filterParams['search_username'], ENT_QUOTES, 'UTF-8') . "\n");
    }
    $pdf->Ln(2);

    $report_student_query = "SELECT sl.*, s.username FROM student_log sl INNER JOIN student s ON sl.student_id = s.student_id";
    $report_teacher_query = "SELECT tl.*, t.username FROM teacher_log tl INNER JOIN teacher t ON tl.teacher_id = t.teacher_id";

    $where_clause = " WHERE 1=1 ";

    if (isset($filterParams['start_date']) && $filterParams['start_date'] != '') {
        $report_start_date = mysqli_real_escape_string($con, $filterParams['start_date']);
        $where_clause .= " AND login_time >= '$report_start_date' ";
    }

    if (isset($filterParams['end_date']) && $filterParams['end_date'] != '') {
        $report_end_date = mysqli_real_escape_string($con, $filterParams['end_date']);
        $where_clause .= " AND login_time <= '$report_end_date' ";
    }

    $search_username = isset($filterParams['search_username']) ? mysqli_real_escape_string($con, $filterParams['search_username']) : '';
    $student_username_clause = " AND s.username LIKE '%$search_username%'";
    $teacher_username_clause = " AND t.username LIKE '%$search_username%'";

    if (isset($filterParams['user_type']) && $filterParams['user_type'] != '') {
        $report_user_type = mysqli_real_escape_string($con, $filterParams['user_type']);
        if ($report_user_type == 'student') {
            $report_student_query .= $where_clause . $student_username_clause;
            $report_teacher_query = "";
        } else {
            $report_teacher_query .= $where_clause . $teacher_username_clause;
            $report_student_query = "";
        }
    } else {
        $report_student_query .= $where_clause . $student_username_clause;
        $report_teacher_query .= $where_clause . $teacher_username_clause;
    }

    $data = array();

    if (!empty($report_student_query)) {
        $report_student_log_query = mysqli_query($con, $report_student_query . " ORDER BY login_time DESC") or die(mysqli_error($con));
        while ($report_student_log_row = mysqli_fetch_array($report_student_log_query)) {
            $data[] = array(
                'user_id' => $report_student_log_row['student_id'],
                'username' => $report_student_log_row['username'],
                'user_type' => 'Estudiante',
                'login_time' => $report_student_log_row['login_time'],
                'logout_time' => $report_student_log_row['logout_time'] ? $report_student_log_row['logout_time'] : 'Aún en línea'
            );
        }
    }

    if (!empty($report_teacher_query)) {
        $report_teacher_log_query = mysqli_query($con, $report_teacher_query . " ORDER BY login_time DESC") or die(mysqli_error($con));
        while ($report_teacher_log_row = mysqli_fetch_array($report_teacher_log_query)) {
            $data[] = array(
                'user_id' => $report_teacher_log_row['teacher_id'],
                'username' => $report_teacher_log_row['username'],
                'user_type' => 'Docente',
                'login_time' => $report_teacher_log_row['login_time'],
                'logout_time' => $report_teacher_log_row['logout_time'] ? $report_teacher_log_row['logout_time'] : 'Aún en línea'
            );
        }
    }

    $userLogHeaders = ['ID del usuario', 'Usuario', 'Tipo de usuario', 'Inicio de sesión', 'Cierre de sesión'];
    $userLogDataFormatted = array_map(function ($row) {
        return [
            $row['user_id'],
            $row['username'],
            $row['user_type'],
            $row['login_time'],
            $row['logout_time']
        ];
    }, $data);

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

    $pdf->Output('reporte_historial_login.pdf', 'D');
}

if (isset($_POST['generate_report'])) {
    $filterParams = array(
        'start_date' => isset($_POST['start_date']) ? $_POST['start_date'] : '',
        'end_date' => isset($_POST['end_date']) ? $_POST['end_date'] : '',
        'user_type' => isset($_POST['user_type']) ? $_POST['user_type'] : '',
        'search_username' => isset($_POST['search_username']) ? $_POST['search_username'] : ''
    );

    generateLoginHistoryReport($con, $filterParams, $organization);
}