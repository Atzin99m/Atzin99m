<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "mntto_cazel");
if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conexion->connect_error]));
}

// Función auxiliar para sanitizar las horas
function completarHora($hora) {
    if (!$hora || trim($hora) === '') return null;
    return strlen($hora) === 5 ? $hora . ':00' : $hora;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Modo de Inserción o Actualización
    $id = $_POST['id'] ?? null;
    
    // Obtener y sanitizar valores del formulario
    $no_empl = $_POST['no_empl'] ?? null;
    $Nombre = $_POST['Nombre'] ?? null;
    $turno = $_POST['turno'] ?? null;
    $no_orden = $_POST['no_orden'] ?? null;
    $Recepcion = $_POST['Recepcion'] ?? null;
    $recep_maquina = $_POST['recep_maquina'] ?? null;
    $Tipo = $_POST['Tipo'] ?? null;
    $Hr_ini = completarHora($_POST['Hr_ini'] ?? '');
    $Hr_Fin = completarHora($_POST['Hr-Fin'] ?? '');
    $time_area = completarHora($_POST['time_area'] ?? '');
    $t_extra = completarHora($_POST['t_extra'] ?? '');
    $t_realizado = $_POST['t_realizado'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;
    $Estatus = $_POST['Estatus'] ?? null;

    // Validación básica de datos
    if (empty($no_empl) || empty($Nombre) || empty($turno) || empty($no_orden)) {
        echo json_encode(["mensaje" => "Error: Faltan datos obligatorios.", "success" => false]);
        exit;
    }

    if ($id === "0" || $id === null) {
        // Lógica de Inserción (INSERT)
        $stmt = $conexion->prepare("INSERT INTO tecnicos 
            (no_empl, Nombre, turno, no_orden, Recepcion, recep_maquina, Tipo, Hr_ini, `Hr-Fin`, 
            time_area, t_extra, t_realizado, observaciones, Estatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            echo json_encode(["mensaje" => "Error en la preparación: " . $conexion->error, "success" => false]);
            exit;
        }

        $stmt->bind_param("isssisssssssss", 
            $no_empl, $Nombre, $turno, $no_orden, $Recepcion, $recep_maquina, $Tipo,
            $Hr_ini, $Hr_Fin, $time_area, $t_extra, $t_realizado, $observaciones, $Estatus);
        
        if ($stmt->execute()) {
            $response = ["mensaje" => "Registro insertado correctamente", "success" => true];
        } else {
            $response = ["mensaje" => "Error al insertar: " . $stmt->error, "success" => false];
        }
    } else {
        // Lógica de Actualización (UPDATE)
        $sql = "UPDATE tecnicos SET 
            no_empl=?, Nombre=?, turno=?, no_orden=?, Recepcion=?, recep_maquina=?, Tipo=?, Hr_ini=?, `Hr-Fin`=?, time_area=?, t_extra=?, t_realizado=?, observaciones=?, Estatus=?
            WHERE id=?";
        $stmt = $conexion->prepare($sql);
        
        if (!$stmt) {
            echo json_encode(["mensaje" => "Error en la preparación: " . $conexion->error, "success" => false]);
            exit;
        }
        
        $stmt->bind_param("isssissssssssi", 
            $no_empl, $Nombre, $turno, $no_orden, $Recepcion, $recep_maquina, $Tipo,
            $Hr_ini, $Hr_Fin, $time_area, $t_extra, $t_realizado, $observaciones, $Estatus, $id);

        if ($stmt->execute()) {
            $response = ["mensaje" => "Registro actualizado correctamente", "success" => true];
        } else {
            $response = ["mensaje" => "Error al actualizar: " . $stmt->error, "success" => false];
        }
    }

    $stmt->close();
} else {
    // Modo de Consulta (GET)
    $result = $conexion->query("SELECT * FROM tecnicos");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $response = $rows;
}

$conexion->close();
echo json_encode($response);?>