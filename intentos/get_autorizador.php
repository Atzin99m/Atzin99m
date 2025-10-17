<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'connection.php'; // Asegúrate de que este archivo exista y contenga la conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "mntto_cazel";

$conexion = new mysqli($server, $user, $pass, $db);
$conexion->set_charset("utf8mb4");

if ($conexion->connect_errno) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_documento'])) {
    $id_documento = $_POST['id_documento'];
    
    // Obtener y sanitizar datos del POST
    $solicitante = $_POST["solicitante"] ?? '';
    // ... (resto de campos como en connection.php)
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
    
    // Preparar la sentencia UPDATE
    $stmt = $conexion->prepare("UPDATE orden_trabajo SET 
        departamento = ?, maquina = ?, seccion = ?, solicitante = ?, `autorizado por` = ?, Estatus = ?, 
        descripcion = ?, tipo = ?, fh_emision = ?, observaciones = ?, prioridad = ?, paro = ?
        WHERE id_documento = ?");

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "Error en la preparación de la consulta: " . $conexion->error
        ]);
        exit;
    }

    $estatus = "Modificado"; // Puedes cambiar el estatus a "Modificado"

    $stmt->bind_param(
        "ssssissssssii", // El último 'i' es para el id_documento
        $departamento,
        $maquina,
        $seccion,
        $solicitante,
        $autorizacion_id,
        $estatus,
        $descripcion,
        $tipo,
        $fecha,
        $observaciones,
        $prioridad,
        $paro,
        $id_documento
    );

    if ($stmt->execute()) {
        // Obtener el nombre del autorizador para la respuesta JSON
        $nombre_autorizador = '';
        if (!empty($autorizacion_id)) {
            $res = $conexion->query("SELECT Nombre FROM autorizacion WHERE no_empl = '$autorizacion_id'");
            if ($row = $res->fetch_assoc()) {
                $nombre_autorizador = $row['Nombre'];
            }
        }
        
        echo json_encode([
            "success" => true,
            "message" => "Registro actualizado exitosamente",
            "data" => [
                "id" => $id_documento,
                "fecha" => $fecha,
                "departamento" => $departamento,
                "seccion" => $seccion,
                "descripcion" => $descripcion,
                "observaciones" => $observaciones,
                "tipo" => $tipo,
                "prioridad" => $prioridad,
                "maquina" => $maquina,
                "solicitante" => $solicitante,
                "autorizacion" => $nombre_autorizador,
                "paro" => $paro,
                "estatus" => $estatus
            ]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al actualizar: " . $stmt->error
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Petición inválida. Se requiere un ID para actualizar."
    ]);
}

$conexion->close();
?>