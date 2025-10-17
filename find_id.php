<?php
$conexion = new mysqli("localhost", "root", "", "cazel_all");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$no_orden = $_GET['no_orden'];
$sql = "SELECT descripcion FROM orden_trabajo WHERE id_documento = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $no_orden);
$stmt->execute();
$result = $stmt->get_result();

$response = ["descripcion" => null];
if ($row = $result->fetch_assoc()) {
    $response["descripcion"] = $row["descripcion"];
}

header('Content-Type: application/json');
echo json_encode($response);

$conexion->close();
?>
