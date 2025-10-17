<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "mntto_cazel");
$conexion->set_charset("utf8mb4");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$action = $_POST['action'] ?? '';
$username = $_POST["username"] ?? '';
$passw = $_POST["passw"] ?? '';
$no_empl = $_POST["no_empl"] ?? '';
$area = $_POST["area"] ?? '';

function mostrarError($mensaje) {
    echo htmlspecialchars($mensaje);
    exit();
}

if ($action === 'Registrar') {
    // Lógica de registro
    if (empty($username) || empty($passw) || empty($no_empl) || empty($area)) {
        mostrarError("Todos los campos son obligatorios para el registro.");
    }

    // 1. Hashear la contraseña para almacenarla de forma segura
    $passw_hash = password_hash($passw, PASSWORD_DEFAULT);

    // 2. Usar sentencia preparada para el registro
    $stmt = $conexion->prepare("INSERT INTO autorizacion (no_empl, nombre, passw, area) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        mostrarError("Error en la preparación de la consulta: " . $conexion->error);
    }
    
    // 'isss' indica que los parámetros son un entero y tres cadenas (string)
    $stmt->bind_param("isss", $no_empl, $username, $passw_hash, $area);
    
    if ($stmt->execute()) {
        $_SESSION["usuario"] = $username;
        $_SESSION["no_empl"] = $no_empl;
        $_SESSION["area"] = $area;
        header("Location: autoritation.php");
        exit();
    } else {
        mostrarError("Error al registrar: " . $stmt->error);
    }
    $stmt->close();

} elseif ($action === 'Entrar') {
    // Lógica de inicio de sesión
    if (empty($no_empl) || empty($passw) || empty($area)) {
        mostrarError("Todos los campos son obligatorios para iniciar sesión.");
    }

    // 1. Usar sentencia preparada para la consulta
    $stmt = $conexion->prepare("SELECT no_empl, nombre, passw, area FROM autorizacion WHERE no_empl = ?");
    if (!$stmt) {
        mostrarError("Error en la preparación de la consulta: " . $conexion->error);
    }
    $stmt->bind_param("i", $no_empl);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // 2. Verificar la contraseña hasheada
        if (password_verify($passw, $fila["passw"])) {
            if ($area === $fila["area"]) {
                $_SESSION["usuario"] = $fila["nombre"];
                $_SESSION["no_empl"] = $fila["no_empl"];
                $_SESSION["area"] = $fila["area"];
                header("Location: autoritation.php");
                exit();
            } else {
                mostrarError("El área seleccionada no coincide con la registrada.");
            }
        } else {
            mostrarError("Número de empleado o contraseña incorrectos.");
        }
    } else {
        mostrarError("Número de empleado o contraseña incorrectos.");
    }
    $stmt->close();
}

$conexion->close();
?>