<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
  date_default_timezone_set('America/Mexico_City');
header('Content-Type: application/json; charset=utf-8');

// --- Conexión ---
$conexion = new mysqli("localhost", "root", "", "cazel_all");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "mensaje" => "Error de conexión: " . $conexion->connect_error]));
}

// --- MÉTODO GET: listar registros ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $no_orden = $_GET['no_orden'] ?? "";
    $sql = "SELECT * FROM tecnicos";
    if (!empty($no_orden)) {
        $sql .= " WHERE no_orden = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $no_orden);
    } else {
        $stmt = $conexion->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
    $stmt->close();
    $conexion->close();
    exit;
}

// --- MÉTODO POST: insertar nuevo registro ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $campos = [
    'no_orden', 'Nombre', 'turno', 'Tipo', 'Recepcion',
    'recep_maquina', 'Hr_ini', 't_extra',
    't_realizado', 'observaciones'
  ];
  $valores = [];
  foreach ($campos as $campo) {
    $valores[$campo] = $_POST[$campo] ?? null;
  }

  // Generar hora final automáticamente

  $valores['Hr_Fin'] = date("H:i:s");

  // Calcular tiempo total
  $time_total = "--:--";
  if (!empty($valores['Hr_ini']) && !empty($valores['Hr_Fin'])) {
    $inicio = new DateTime($valores['Hr_ini']);
    $fin = new DateTime($valores['Hr_Fin']);
    $interval = $inicio->diff($fin);
    $totalMin = $interval->h * 60 + $interval->i;

    if (!empty($valores['t_extra'])) {
      [$h, $m] = explode(':', $valores['t_extra']);
      $totalMin += ($h * 60) + $m;
    }

    $horas = floor($totalMin / 60);
    $minutos = $totalMin % 60;
    $time_total = sprintf("%02d:%02d", $horas, $minutos);
  }

  $estatus = "Terminado y Clasurado";

  $sql = "INSERT INTO tecnicos (
    no_orden, Nombre, turno, Tipo, Recepcion, recep_maquina,
    Hr_ini, Hr_Fin, t_extra, t_realizado, observaciones, Estatus
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param(
    "isssssssssss",
    $valores['no_orden'],
    $valores['Nombre'],
    $valores['turno'],
    $valores['Tipo'],
    $valores['Recepcion'],
    $valores['recep_maquina'],
    $valores['Hr_ini'],
    $valores['Hr_Fin'],
    $valores['t_extra'],
    $valores['t_realizado'],
    $valores['observaciones'],
    $estatus
  );

  if ($stmt->execute()) {
    echo json_encode(["success" => true, "mensaje" => "Orden guardada y clausurada."]);
  } else {
    echo json_encode(["success" => false, "mensaje" => "Error al guardar: " . $stmt->error]);
  }

  $stmt->close();
  $conexion->close();
  exit;
}
// --- MÉTODO PUT: actualizar campo editable ---
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'] ?? null;
    $campo = $put_vars['campo'] ?? null;
    $valor = $put_vars['valor'] ?? null;

    if (!$id || !$campo) {
        echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
        exit;
    }

    $sql = "UPDATE tecnicos SET $campo = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $valor, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "mensaje" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
    exit;
}

echo json_encode(["success" => false, "mensaje" => "Método no permitido."]);
?>