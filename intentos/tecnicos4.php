<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "cazel_all");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "mensaje" => "Conexión fallida: " . $conexion->connect_error]));
}

function completarHora($hora) {
    if (!$hora || trim($hora) === '') return "";
    return strlen($hora) === 5 ? $hora . ':00' : $hora;
}

// ✅ Manejo de actualización desde tabla HTML (PUT)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'] ?? null;
    $campo = $put_vars['campo'] ?? null;
    $valor = $put_vars['valor'] ?? null;

    if (!$id || !$campo) {
        echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
        exit;
    }

    $campos_validos = [ 'Nombre', 'turno', 'no_orden', 'Recepcion', 'recep_maquina', 'Tipo', 
    'Hr_ini', 'Hr_Fin',  't_extra', 't_realizado', 'observaciones', 'Estatus'];
    if (!in_array($campo, $campos_validos)) {
        echo json_encode(["success" => false, "mensaje" => "Campo inválido"]);
        exit;
    }

    // Si se actualiza no_orden, verificar que exista en orden_trabajo
    if ($campo === 'no_orden') {
        $verificaOrden = $conexion->prepare("SELECT COUNT(*) FROM orden_trabajo WHERE id_documento = ?");
        $verificaOrden->bind_param("i", $valor);
        $verificaOrden->execute();
        $verificaOrden->bind_result($existeOrden);
        $verificaOrden->fetch();
        $verificaOrden->close();

        if ($existeOrden == 0) {
            echo json_encode(["success" => false, "mensaje" => "La orden de trabajo no existe"]);
            exit;
        }
    }

    $stmt = $conexion->prepare("UPDATE tecnicos SET $campo = ? WHERE id = ?");
    $stmt->bind_param("si", $valor, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
    exit;
}

// ✅ Manejo de inserción y actualización (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
       $Nombre = $_POST['Nombre'] ?? null;
        $no_orden = $_POST['no_orden'] ?? null; 
        $turno = $_POST['turno'] ?? null;
          $time_area = $_POST['time_area'] ?? null;
    $Recepcion = $_POST['Recepcion'] ?? null;
    $recep_maquina = $_POST['recep_maquina'] ?? null;
    $Tipo = $_POST['Tipo'] ?? '';
    $Hr_ini = completarHora($_POST['Hr_ini'] ?? '');
    $Hr_Fin = completarHora($_POST['Hr_Fin'] ?? '');
       $t_extra = completarHora($_POST['t_extra'] ?? '');
    $t_realizado = $_POST['t_realizado'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;
    $Estatus = "Terminado";

    if (empty($Nombre) || empty($turno) || empty($no_orden) ||  empty($Tipo) || empty($Hr_Fin) ||empty($t_realizado)
    ) {
        echo json_encode(["success" => false, "mensaje" => "Error: Faltan datos obligatorios."]);
        exit;
    }

    // Verificar si la orden de trabajo existe
    $verificaOrden = $conexion->prepare("SELECT COUNT(*) FROM orden_trabajo WHERE id_documento = ?");
    $verificaOrden->bind_param("i", $no_orden);
    $verificaOrden->execute();
    $verificaOrden->bind_result($existeOrden);
    $verificaOrden->fetch();
    $verificaOrden->close();

    if ($existeOrden == 0) {
        echo json_encode(["success" => false, "mensaje" => "Error: La orden de trabajo no existe."]);
        exit;
    }

    if ($id === "0" || $id === null) {
        $stmt = $conexion->prepare("INSERT INTO tecnicos 
            (Nombre, turno, no_orden, Recepcion, recep_maquina, Tipo, Hr_ini, Hr_Fin, 
            time_area, t_extra, t_realizado, observaciones, Estatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssss", 
            $Nombre, $turno, $no_orden, $Recepcion, $recep_maquina, $Tipo,
            $Hr_ini, $Hr_Fin, $time_area, $t_extra, $t_realizado, $observaciones, $Estatus);
    } else {
        $stmt = $conexion->prepare("UPDATE tecnicos SET 
            Nombre=?, turno=?, no_orden=?, Recepcion=?, recep_maquina=?, Tipo=?, Hr_ini=?, 
            Hr_Fin=?, time_area=?, t_extra=?, t_realizado=?, observaciones=?, Estatus=?
            WHERE id=?");

        $stmt->bind_param("sssssssssssssi", 
            $Nombre, $turno, $no_orden, $Recepcion, $recep_maquina, $Tipo,
            $Hr_ini, $Hr_Fin, $time_area, $t_extra, $t_realizado, $observaciones, $Estatus, $id);
    }
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "mensaje" => "Registro guardado correctamente"]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
    exit;
}

// ✅ Manejo de consulta (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Nombre'])) {
    $Nombre = $_GET['Nombre'] ?? '';
    $stmt = $conexion->prepare("SELECT * FROM tecnicos WHERE Nombre = ?");
    $stmt->bind_param("s", $Nombre);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    $stmt->close();
    $conexion->close();
    exit;
}
$result = $conexion->query("SELECT * FROM tecnicos");
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);

$conexion->close();
?>









































