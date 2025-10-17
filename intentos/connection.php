<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    /*case 'update':
        updateOrder($conexion);
        break;
    case 'get_autorizador':
        getAutorizadorName($conexion);
        break;*/
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
    $solicitante = $_POST["solicitante"] ?? '';
    $departamento = $_POST["mopcion"] ?? '';
    $maquina = $_POST["Maquina"] ?? '';
   // $seccion = $_POST["area"] ?? '';
    $fecha = $_POST["fecha_emision"] ?? date('Y-m-d H:i:s');
    //$autorizacion_id = $_POST["autorizacion"] ?? null;
    $descripcion = $_POST["descripcion_necesidad"] ?? '';
    //$observaciones = $_POST["observaciones"] ?? null;
    $prioridad = $_POST["prioridad"] ?? '';
    $tipo = $_POST["tipo-servicio"] ?? '';
    $paro = (($_POST["paro"] ?? 'no') === 'si') ? 1 : 0;
    $estatus = "Pendiente";

    if (empty($solicitante) || empty($departamento) || empty($maquina) || empty($descripcion) || empty($prioridad) || empty($tipo)) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
        return;
    }

    $stmt = $conexion->prepare("INSERT INTO orden_trabajo 
        (departamento, maquina, solicitante, Estatus, descripcion, tipo, fh_emision, prioridad, paro) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error en la preparación: " . $conexion->error]);
        return;
    }

    $stmt->bind_param(
        "ssssissssssi", 
        $departamento, $maquina, $solicitante, $estatus, 
        $descripcion, $tipo, $fecha, $prioridad, $paro
    );

    if ($stmt->execute()) {
        $ultimoID = $conexion->insert_id;
        //$nombre_autorizador = getAutorizadorNameFromDB($conexion, $autorizacion_id);
        
        echo json_encode([
            "success" => true,
            "message" => "Registro exitoso",
            "data" => [
                "id_documento" => $ultimoID,
                "fh_emision" => $fecha,
                "departamento" => $departamento,
                //"seccion" => $seccion,
                "descripcion" => $descripcion,
                //"observaciones" => $observaciones,
                "tipo" => $tipo,
                "prioridad" => $prioridad,
                "maquina" => $maquina,
                "solicitante" => $solicitante,
                //"autorizado_por" => $autorizacion_id,
                //"nombre_autorizador" => $nombre_autorizador,
                "paro" => (string)$paro,
                "Estatus" => $estatus,
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al insertar: " . $stmt->error]);
    }
    $stmt->close();
}

/*function updateOrder($conexion) {
    $id_documento = $_POST['id_documento'] ?? null;
    if (empty($id_documento)) {
        echo json_encode(["success" => false, "message" => "ID del documento no proporcionado."]);
        return;
    }
    
    // Obtener y sanitizar datos del POST
    $solicitante = $_POST["solicitante"] ?? '';
    $departamento = $_POST["mopcion"] ?? '';
    $maquina = $_POST["Maquina"] ?? '';
    $seccion = $_POST["area"] ?? '';
    $fecha = $_POST["fecha_emision"] ?? date('Y-m-d H:i:s');
    $autorizacion_id = $_POST["autorizacion"] ?? null;
    $descripcion = $_POST["descripcion_necesidad"] ?? '';
    $observaciones = $_POST["observaciones"] ?? null;
    $prioridad = $_POST["prioridad"] ?? '';
    $tipo = $_POST["tipo-servicio"] ?? '';
    $paro = (($_POST["paro"] ?? 'no') === 'si') ? 1 : 0;
    $estatus = "Modificado";

   $stmt = $conexion->prepare("UPDATE orden_trabajo SET 
        departamento = ?, maquina = ?, seccion = ?, solicitante = ?, `autorizado por` = ?, Estatus = ?, 
        descripcion = ?, tipo = ?, fh_emision = ?, observaciones = ?, prioridad = ?, paro = ?
        WHERE id_documento = ?");

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error en la preparación: " . $conexion->error]);
        return;
    }

    $stmt->bind_param(
        "ssssissssssii", 
        $departamento, $maquina, $seccion, $solicitante, $autorizacion_id, $estatus, 
        $descripcion, $tipo, $fecha, $observaciones, $prioridad, $paro, $id_documento
    );

    if ($stmt->execute()) {
        $nombre_autorizador = getAutorizadorNameFromDB($conexion, $autorizacion_id);
        
        echo json_encode([
            "success" => true,
            "message" => "Registro actualizado exitosamente",
            "data" => [
                "id_documento" => $id_documento,
                "fh_emision" => $fecha,
                "departamento" => $departamento,
                "seccion" => $seccion,
                "descripcion" => $descripcion,
                "observaciones" => $observaciones,
                "tipo" => $tipo,
                "prioridad" => $prioridad,
                "maquina" => $maquina,
                "solicitante" => $solicitante,
                "autorizado_por" => $autorizacion_id,
                "nombre_autorizador" => $nombre_autorizador,
                "paro" => (string)$paro,
                "Estatus" => $estatus,
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar: " . $stmt->error]);
    }
    $stmt->close();
}*/

function getAutorizadorName($conexion) {
    $no_empl = $_GET["no_empl"] ?? '';
    if (empty($no_empl)) {
        echo json_encode(["success" => false, "message" => "Número de empleado no proporcionado."]);
        return;
    }
    echo json_encode(["success" => true, "nombre" => getAutorizadorNameFromDB($conexion, $no_empl)]);
}

function getAutorizadorNameFromDB($conexion, $no_empl) {
    $nombre = '';
    if (!empty($no_empl)) {
        $no_empl = $conexion->real_escape_string($no_empl);
        $sql = "SELECT Nombre FROM autorizacion WHERE no_empl = '$no_empl'";
        $result = $conexion->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nombre = $row['Nombre'];
        }
    }
    return $nombre;
}

function getAllOrders($conexion) {
   $no_orden = $_GET['no_orden'] ?? '';  // o $_POST dependiendo del método
    $sql = "SELECT ot.id_documento, ot.departamento, ot.maquina, ot.seccion, ot.solicitante, ot.`autorizado por` AS autorizado_por,
                  ot.Estatus, ot.descripcion, ot.tipo, ot.fh_emision, ot.observaciones, ot.prioridad, ot.paro,
                  a.Nombre AS nombre_autorizador
            FROM orden_trabajo ot
            LEFT JOIN autorizacion a ON ot.`autorizado por` = a.no_empl";

 if (!empty($no_orden)) {
        $no_orden = $conexion->real_escape_string($no_orden);
        $sql .= " WHERE ot.id_documento = '$no_orden'";
    }
    $result = $conexion->query($sql);
    $orders = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
    echo json_encode(["success" => true, "data" => $orders]);
}
?>
 









 






