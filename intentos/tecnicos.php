<?php
header('Content-Type: application/json');

$host = "localhost";
$db = "mntto_cazel";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
    exit;
}

// CONSULTA: Ver técnicos y órdenes de trabajo
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["ver"])) {
    try {
        $tecnicos = $pdo->query("SELECT * FROM tecnicos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $ordenes = $pdo->query("SELECT * FROM orden_trabajo ORDER BY id_documento DESC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["tecnicos" => $tecnicos, "ordenes" => $ordenes]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// INSERCIÓN: Agregar técnico
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "insertar_tecnico") {
    $nombre = $_POST["nombre"] ?? '';
    $turno = $_POST["turno"] ?? '';
    $no_empleado = $_POST["no_empleado"] ?? '';

    if (empty($nombre) || empty($turno) || !is_numeric($no_empleado)) {
        echo json_encode(["success" => false, "message" => "Datos inválidos para técnico."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO tecnicos (no_empl, Nombre, turno) VALUES (?, ?, ?)");
        $stmt->execute([$no_empleado, $nombre, $turno]);
        echo json_encode(["success" => true, "message" => "Técnico insertado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// ACTUALIZACIÓN: Editar orden de trabajo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "editar_orden") {
    $id_documento = $_POST["id_documento"];
    $estatus = $_POST["status"] ?? '';
    $fh_recepcion = $_POST["fh_recepcion"] ?? null;
    $tiempo_estimado = $_POST["tiempo_estimado"] ?? null;
    $tE_maq = $_POST["tE_maq"] ?? null;
    $t_extra = $_POST["t_extra"] ?? 0;
    $observaciones = $_POST["observaciones"] ?? null;
    $tecnico = $_POST["tecnico"] ?? null;
    $tipo = $_POST["tipo"] ?? '';
    $tp_area = $_POST["tp_area"] ?? null;
    $temp_paro = $_POST["temp_paro"] ?? null;
    $fh_maquina = $_POST["fh_maquina"] ?? null;
    $descripcion = $_POST["descripcion"] ?? '';

    try {
        $stmt = $pdo->prepare("UPDATE orden_trabajo SET
            status = ?, fh_recepcion = ?, tiempo_estimado = ?, tE_maq = ?, t_extra = ?,
            observaciones = ?, tecnico = ?, tipo = ?, tp_area = ?, temp_paro = ?, fh_maquina = ?,
            descripcion = ?
            WHERE id_documento = ?");

        $stmt->execute([
            $estatus, $fh_recepcion, $tiempo_estimado, $tE_maq, $t_extra,
            $observaciones, $tecnico, $tipo, $tp_area, $temp_paro, $fh_maquina,
            $descripcion, $id_documento
        ]);

        echo json_encode(["success" => true, "message" => "Orden actualizada correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit;
}
?>
