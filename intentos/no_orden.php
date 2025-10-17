<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "mntto_cazel");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Conexión fallida: " . $conexion->connect_error]));
}

// Leer el ID
$no_orden = $_GET['no_orden'] ?? '';

if ($no_orden === '') {
    echo json_encode(["success" => false, "message" => "ID no proporcionado"]);
    exit;
}

// Consulta preparada
$sql = "SELECT * FROM orden_trabajo WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $no_orden);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "data" => $row
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Orden no encontrada"
    ]);
}

$stmt->close();
$conexion->close();
?>
