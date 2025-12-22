<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

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
    $solicitante  = $_POST["solicitante"] ?? '';
  $departamento = $_POST["mopcion"] ?? '';
  $maquina      = $_POST["maquina"] ?? '';
 $fecha        = $_POST["fecha_emision"] ?? date('Y-m-d H:i:s');
$descripcion = $_POST['descripcion'] ?? '';
 $paro         = strtolower($_POST['paro'] ?? '') === "si" ? "Sí" : "No";
    $estatus = "Pendiente";

    if (empty($solicitante) || empty($departamento) || empty($maquina) || empty($descripcion) ||
      empty($paro)) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
        return;
    }

    $stmt = $conexion->prepare("INSERT INTO orden_trabajo
(departamento, maquina, solicitante, Estatus, descripcion, fh_emision, paro)
VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error en la preparación: " . $conexion->error]);
        return;
    }

    $stmt->bind_param("sssssss", $departamento, $maquina, $solicitante, $estatus, $descripcion, $fecha, $paro);

    if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Orden registrada correctamente. N° de orden: " . $stmt->insert_id,
        "data" => [
            "id_documento" => $stmt->insert_id,   // último ID insertado
            "fh_emision"   => $fecha,
            "departamento" => $departamento,
            "descripcion"  => $descripcion,
            "maquina"      => $maquina,
            "solicitante"  => $solicitante,
            "paro"         => $paro,
            "Estatus"      => $estatus 
           
        ] 
       
    ]);
// ===============================
// CREAR MENSAJE DE ORDEN DE MANTENIMIENTO (SIN EJECUTAR WHATSAPP)
// ===============================
$id = $stmt->insert_id;
// Generar nombre del archivo con el ID o folio de la orden
$marchivo1 = "OT-MANTTO-" . $id;  // Usa aquí el ID real de tu orden

// Ruta donde se guardará el archivo
$march = "//192.168.0.96/Proyectos_Cazel/Archivos/Entrada/" . $marchivo1 . ".csv";

// Crear archivo CSV
$marchivo = fopen($march, 'w');

if ($marchivo === false) {
   die('Error al crear el archivo del mensaje.');
}

// Contenido del mensaje
$mproceso = "Orden_Mantenimiento";
$mmensaje = "Se generó orden de mantenimiento para la máquina: " . $maquina .
                    " con  Folio: " . $id;
// Redirigir a la página de técnicos con el ID de la orden
echo "<a href='//tecnicos.html?orden=" . $id . "'>Ver Orden</a>";

// Guardar información en el CSV
$mdatos =[$mmensaje, $mproceso];
fputcsv($marchivo, $mdatos, ',');

// Cerrar archivo
fclose($marchivo);

// Guardar referencia del archivo en sesión (para usarlo en otra página)
$_SESSION['archivo_mensaje'] = $marchivo1;

// Alerta de confirmación
//echo "<script>alert('Orden guardada y mensaje generado correctamente. Folio: $id');</script>";





    /*$id = $stmt->insert_id;
    $marchivo1 = "Orden_MNTTO-".$fecha."-".$maquina."-".$id.".csv";
     $march = '\\\\192.168.0.96\\Proyectos_Cazel\\Archivos\\Entrada\\' . $marchivo1;
    $file1 = fopen(filename: $march, mode: 'wb');
    // Verificar si se pudo abrir el archivo
                                if ($file1 === false) {
                                   die('Error al abrir el archivo: ' . $march);
                                } 
                                  // Escribir los datos en el archivo CSV
                                $mproceso = "Orden_Mantenimiento." ;
                                //$mproceso = "Prueba"; 
                                $url_redirigir = "//tecnicos.html";
                                $mmensaje = "Se Genero una orden de trabajo en la maquina" . $maquina . " y 
                                 con folio: " . $url_redirigir . "?orden=" . $id ;


                                $mdatos = [$mmensaje, $mproceso];
                                fputcsv($file1,$mdatos, separator: ',');
                                // Cerrar el archivo
                                fclose($file1);                  

                               $archivo_exe = "\\\\192.168.0.96\\Proyectos_Cazel\\whatsapp.exe"; // Ruta completa al archivo .exe
                               $archivo_exe = "c:/Proyectos_Cazel/whatsapp.exe"; // Ruta completa al archivo .exe                  
                            //   $output = []; shell_exec($archivo_exe);
                              // echo $output; // Muestra la salida del comando, si la hay      
                              // $return_var = 0;
                              exec($archivo_exe, $output, $return_var);*/
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $stmt->error
    ]);
} 
$stmt->close();
}
function getAutorizadorName($conexion) {
    $no_empl = $_GET["no_empl"] ?? '';
    if (empty($no_empl)) {
        echo json_encode(["success" => false, "message" => "Número de empleado no proporcionado."]);
        return;
    }
    echo json_encode(["success" => true, "nombre" => getAutorizadorNameFromDB($conexion, $no_empl)]);
}



function getAllOrders($conexion) {
    // Leer JSON desde el cuerpo de la solicitud
    $input = json_decode(file_get_contents("php://input"), true);
    $no_orden = $input['no_orden'] ?? '';

    if (!empty($no_orden)) {
        $stmt = $conexion->prepare("SELECT ot.id_documento, ot.departamento, ot.maquina, ot.solicitante,
            ot.Estatus, ot.descripcion, ot.fh_emision , ot.paro
            FROM orden_trabajo ot
            WHERE ot.id_documento = ?");
        $stmt->bind_param("s", $no_orden);
    } else {
        $stmt = $conexion->prepare("SELECT ot.id_documento, ot.departamento, ot.maquina, ot.solicitante,
            ot.Estatus, ot.descripcion,  ot.fh_emision ,  ot.paro
            FROM orden_trabajo ot");
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode(["success" => true, "data" => $orders]);
} 
?>










 






