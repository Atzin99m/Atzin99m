<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// --- Recibir ID ---
$id = $_POST['id'] ?? ($_GET['id'] ?? null);
if (!$id) {
    die("ID no proporcionado");
}

// --- Conexión a BD ---
$conexion = new mysqli("localhost", "root", "", "cazel_all");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// --- Consulta datos de técnicos ---
$sql = "SELECT * FROM tecnicos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tecnico = $result->fetch_assoc();
$stmt->close();
$conexion->close();

if (!$tecnico) {
    die("No se encontró el registro con ID $id.");
}

// 2. Cargar la plantilla Excel
$templateFile = __DIR__ . "/orden_trabajo.xlsx"; // ruta absoluta
if (!file_exists($templateFile)) {
    die("No se encuentra la plantilla.");
}
$spreadsheet = IOFactory::load($templateFile);
$sheet = $spreadsheet->getActiveSheet();
// 3. Llenar datos
$row = 3; // ajusta según tu plantilla
$sheet->setCellValue("B{$row}", $tecnico['id']);
$sheet->setCellValue("C{$row}", $tecnico['Nombre']); // corregido
$sheet->setCellValue("D{$row}", $tecnico['turno']);
$sheet->setCellValue("E{$row}", $tecnico['no_orden']);
$sheet->setCellValue("F{$row}", $tecnico['Recepcion']); // corregido
$sheet->setCellValue("G{$row}", $tecnico['recep_maquina']);
$sheet->setCellValue("H{$row}", $tecnico['Tipo']); // corregido
$sheet->setCellValue("I{$row}", $tecnico['Hr_ini']); // corregido
$sheet->setCellValue("J{$row}", $tecnico['Hr_Fin']); // corregido
$sheet->setCellValue("K{$row}", $tecnico['t_extra']);
$sheet->setCellValue("L{$row}", $tecnico['time_total']);
$sheet->setCellValue("M{$row}", $tecnico['t_realizado']);
$sheet->setCellValue("N{$row}", $tecnico['observaciones']);
$sheet->setCellValue("O{$row}", $tecnico['Estatus']); // corregido


// 4. Descargar archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="tecnico_'.$id.'.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public'); // ayuda con IE

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
ob_end_clean(); // limpia buffers de salida
$writer->save('php://output');
exit;

