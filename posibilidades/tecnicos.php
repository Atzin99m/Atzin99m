<?php
header('Content-Type: application/json; charset=utf-8');

// --- Conexión ---
$conexion = new mysqli("localhost", "usuario", "password", "tu_base");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "mensaje" => "Error de conexión"]));
}

// --- Lista única de técnicos ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_tecnicos') {
    $sql = "SELECT DISTINCT no_empl, Nombre, turno FROM tecnicos ORDER BY Nombre ASC";
    $res = $conexion->query($sql);
    $datos = [];
    while ($fila = $res->fetch_assoc()) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
    $conexion->close();
    exit;
}

// --- Obtener registro específico por ID ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['no_empl'])) {
    $id = intval($_GET['no_empl']);
    $sql = "SELECT * FROM tecnicos WHERE id = $id";
    $res = $conexion->query($sql);
    $datos = [];
    while ($fila = $res->fetch_assoc()) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
    $conexion->close();
    exit;
}

// --- Obtener todos los registros ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM tecnicos ORDER BY id DESC";
    $res = $conexion->query($sql);
    $datos = [];
    while ($fila = $res->fetch_assoc()) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
    $conexion->close();
    exit;
}

// --- Insertar o actualizar registro ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = intval($_POST['id']);
    $no_empl      = intval($_POST['no_empl']);
    $Nombre       = $conexion->real_escape_string($_POST['Nombre']);
    $turno        = $conexion->real_escape_string($_POST['turno']);
    $no_orden     = intval($_POST['no_orden']);
    $Tipo         = $conexion->real_escape_string($_POST['Tipo']);
    $Hr_ini       = $_POST['Hr_ini'] ?: NULL;
    $Hr_Fin       = $_POST['Hr_Fin'] ?: NULL;
    $time_area    = $_POST['time_area'] ?: '00:00:00';
    $t_extra      = $_POST['t_extra'] ?: '00:00:00';
    $t_realizado  = $conexion->real_escape_string($_POST['t_realizado']);
    $observaciones= $conexion->real_escape_string($_POST['observaciones']);
    $Estatus      = $conexion->real_escape_string($_POST['Estatus']);

    if ($id === 0) {
        // Insertar
        $sql = "INSERT INTO tecnicos 
        (no_empl, Nombre, turno, no_orden, Tipo, Hr_ini, Hr_Fin, time_area, t_extra, t_realizado, observaciones, Estatus)
        VALUES 
        ($no_empl, '$Nombre', '$turno', $no_orden, '$Tipo', " .
        ($Hr_ini ? "'$Hr_ini'" : "NULL") . ", " .
        ($Hr_Fin ? "'$Hr_Fin'" : "NULL") . ", " .
        "'$time_area', '$t_extra', '$t_realizado', '$observaciones', '$Estatus')";
    } else {
        // Actualizar
        $sql = "UPDATE tecnicos SET 
            no_empl=$no_empl,
            Nombre='$Nombre',
            turno='$turno',
            no_orden=$no_orden,
            Tipo='$Tipo',
            Hr_ini=" . ($Hr_ini ? "'$Hr_ini'" : "NULL") . ",
            Hr_Fin=" . ($Hr_Fin ? "'$Hr_Fin'" : "NULL") . ",
            time_area='$time_area',
            t_extra='$t_extra',
            t_realizado='$t_realizado',
            observaciones='$observaciones',
            Estatus='$Estatus'
        WHERE id=$id";
    }

    if ($conexion->query($sql)) {
        echo json_encode(["success" => true, "mensaje" => "Registro guardado correctamente"]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error al guardar: ".$conexion->error]);
    }

    $conexion->close();
    exit;
}
?>
