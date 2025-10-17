<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// --- Conexión a la base de datos ---
$host = "localhost";
$user = "root";
$pass = "";
$db = "cazel_all";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// --- Consulta a la BD ---
$sql = "SELECT 
            t.id, t.no_empl, t.Nombre, t.turno, t.no_orden,
            t.Recepcion, t.recep_maquina, t.Tipo,
            t.Hr_ini, t.Hr_Fin, t.time_area, t.t_extra,
            t.time_total, t.t_realizado, t.observaciones, t.Estatus
        FROM tecnicos 
        ORDER BY t.id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $no_empl);
$stmt->execute();
$result = $stmt->get_result();

// --- Cargar la plantilla ---
$spreadsheet = IOFactory::load("orden_trabajo.xlsx");
$sheet = $spreadsheet->getActiveSheet();

// --- Rellenar los datos ---
$row = 3; // Empieza a escribir desde la fila 3 (asumiendo fila 1 son encabezados)
while ($r = $result->fetch_assoc()) {
    $sheet->setCellValue("B{$row}", $r['id']);
    $sheet->setCellValue("C{$row}", $r['no_empl']);
    $sheet->setCellValue("D{$row}", $r['Nombre']);
    $sheet->setCellValue("E{$row}", $r['turno']);
    $sheet->setCellValue("F{$row}", $r['no_orden']);
    $sheet->setCellValue("G{$row}", $r['Recepcion']);
    $sheet->setCellValue("H{$row}", $r['recep_maquina']);
    $sheet->setCellValue("I{$row}", $r['Tipo']);
    $sheet->setCellValue("J{$row}", $r['Hr_ini']);
    $sheet->setCellValue("K{$row}", $r['Hr_Fin']);
    $sheet->setCellValue("L{$row}", $r['time_area']);
    $sheet->setCellValue("M{$row}", $r['t_extra']);
    $sheet->setCellValue("N{$row}", $r['time_total']); // Total Horas calculado
    $sheet->setCellValue("O{$row}", $r['t_realizado']);
    $sheet->setCellValue("P{$row}", $r['observaciones']);
    $sheet->setCellValue("Q{$row}", $r['Estatus']);
    $row++;
}

// --- Salida del archivo Excel ---
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="orden_trabajo_reporte.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;

?>
