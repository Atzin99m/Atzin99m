<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

$server = "localhost";
$user = "root";
$pass = "";
$db = "cazel_all";

$conexion = new mysqli($server, $user, $pass, $db);
$conexion->set_charset("utf8mb4");

if ($conexion->connect_errno) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Determinar la acción a realizar
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        registerOrder($conexion);
        break;
   
    case 'get_all_orders':
        getAllOrders($conexion);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Acción no válida."]);
        break;
}

$conexion->close();

function registerOrder($conexion) {
    // Obtener y sanitizar datos del POST
    $solicitante  = $_POST["solicitante"] ?? '';
  $departamento = $_POST["mopcion"] ?? '';
  $maquina      = $_POST["maquina"] ?? '';
 $fecha        = $_POST["fecha_emision"] ?? date('Y-m-d H:i:s');
$descripcion = $_POST['descripcion'] ?? '';
 $paro         = strtolower($_POST['paro'] ?? '') === "si" ? "Sí" : "No";
    $estatus = "Pendiente";

    if (empty($solicitante) || empty($departamento) || empty($maquina) || empty($descripcion) ||
      empty($paro)) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
        return;
    }

    $stmt = $conexion->prepare("INSERT INTO orden_trabajo
(departamento, maquina, solicitante, Estatus, descripcion, fh_emision, paro)
VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error en la preparación: " . $conexion->error]);
        return;
    }

    $stmt->bind_param("sssssss", $departamento, $maquina, $solicitante, $estatus, $descripcion, $fecha, $paro);

    if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Orden registrada correctamente. N° de orden: " . $stmt->insert_id,
        "data" => [
            "id_documento" => $stmt->insert_id,   // último ID insertado
            "fh_emision"   => $fecha,
            "departamento" => $departamento,
            "descripcion"  => $descripcion,
            "maquina"      => $maquina,
            "solicitante"  => $solicitante,
            "paro"         => $paro,
            "Estatus"      => $estatus
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $stmt->error
    ]);
} 
$stmt->close();
}
function getAutorizadorName($conexion) {
    $no_empl = $_GET["no_empl"] ?? '';
    if (empty($no_empl)) {
        echo json_encode(["success" => false, "message" => "Número de empleado no proporcionado."]);
        return;
    }
    echo json_encode(["success" => true, "nombre" => getAutorizadorNameFromDB($conexion, $no_empl)]);
}



function getAllOrders($conexion) {
    // Leer JSON desde el cuerpo de la solicitud
    $input = json_decode(file_get_contents("php://input"), true);
    $no_orden = $input['no_orden'] ?? '';

    if (!empty($no_orden)) {
        $stmt = $conexion->prepare("SELECT ot.id_documento, ot.departamento, ot.maquina, ot.solicitante,
            ot.Estatus, ot.descripcion, ot.fh_emision , ot.paro
            FROM orden_trabajo ot
            WHERE ot.id_documento = ?");
        $stmt->bind_param("s", $no_orden);
    } else {
        $stmt = $conexion->prepare("SELECT ot.id_documento, ot.departamento, ot.maquina, ot.solicitante,
            ot.Estatus, ot.descripcion,  ot.fh_emision ,  ot.paro
            FROM orden_trabajo ot");
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode(["success" => true, "data" => $orders]);
} 
?>










 






