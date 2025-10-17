</*?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "mntto_cazel");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "mensaje" => "Conexión fallida: " . $conexion->connect_error]));
}

// ✅ BLOQUE PARA ACTUALIZAR DESDE LA TABLA HTML
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'] ?? null;
    $campo = $put_vars['campo'] ?? null;
    $valor = $put_vars['valor'] ?? null;

    if (!$id || !$campo) {
        echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
        exit;
    }
    // Validar el campo
function completarHora($hora) {
    if (!$hora || trim($hora) === '') return null;
    return strlen($hora) === 5 ? $hora . ':00' : $hora;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $no_empl = $_POST['no_empl'] ?? null;
    $Nombre = $_POST['Nombre'] ?? null;
    $turno = $_POST['turno'] ?? null;
    $no_orden = $_POST['no_orden'] ?? null;
    $Recepcion = $_POST['Recepcion'] ?? null;
    $recep_maquina = $_POST['recep_maquina'] ?? null;
    $Tipo = $_POST['Tipo'] ?? null;
    $Hr_ini = completarHora($_POST['Hr_ini'] ?? '');
    $Hr_Fin = completarHora($_POST['Hr_Fin'] ?? '');
    $time_area = completarHora($_POST['time_area'] ?? '');
    $t_extra = completarHora($_POST['t_extra'] ?? '');
    $t_realizado = $_POST['t_realizado'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;
    $Estatus = $_POST['Estatus'] ?? null;

    if (empty($no_empl) || empty($Nombre) || empty($turno) || empty($no_orden)) {
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
            (no_empl, Nombre, turno, no_orden, Recepcion, recep_maquina, Tipo, Hr_ini, Hr_Fin, time_area, t_extra, 
            t_realizado, observaciones, Estatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssisssssssss", 
            $no_empl, $Nombre, $turno, $no_orden, $Recepcion, $recep_maquina, $Tipo,
            $Hr_ini, $Hr_Fin, $time_area, $t_extra, $t_realizado, $observaciones, $Estatus);
    } else {
        $stmt = $conexion->prepare("UPDATE tecnicos SET 
            no_empl=?, Nombre=?, turno=?, no_orden=?, Recepcion=?, recep_maquina=?, Tipo=?, Hr_ini=?, Hr_Fin=?, time_area=?, 
            t_extra=?, t_realizado=?, observaciones=?, Estatus=?
            WHERE id=?");

        $stmt->bind_param("isssisssssssssi", 
            $no_empl, $Nombre, $turno, $no_orden, $Recepcion, $recep_maquina, $Tipo,
            $Hr_ini, $Hr_Fin, $time_area, $t_extra, $t_realizado, $observaciones, $Estatus, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "mensaje" => "Registro guardado correctamente"]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    $result = $conexion->query("SELECT * FROM tecnicos");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}

$conexion->close();

    $valid_fields = ['no_empl', 'Nombre', 'turno', 'no_orden', 'Recepcion', 'recep_maquina', 'Tipo', 'Hr_ini', 
    'Hr_Fin', 'time_area', 't_extra', 't_realizado', 'observaciones', 'Estatus'];
    if (!in_array($campo, $valid_fields)) {
        echo json_encode(["success" => false, "mensaje" => "Campo no válido"]);
        exit;
    }

    // Actualizar el campo
    $stmt = $conexion->prepare("UPDATE tecnicos SET $campo = ? WHERE id = ?");
    $stmt->bind_param("si", $valor, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "mensaje" => "Registro actualizado correctamente"]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error: " . $stmt->error]);
    }
    
    $stmt->close();
    exit;
}*/?> 
