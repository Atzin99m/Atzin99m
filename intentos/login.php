<?php 
session_start();

$conexion = new mysqli("localhost", "root", "", "cazel_all");
$conexion->set_charset("utf8mb4");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$username = $_POST["username"];
$passw = $_POST["passw"];   
$no_empl = $_POST["no_empl"] ?? null;
$area = $_POST["area"] ?? null;

if (isset($_POST["Registrar"])) {
    $sql = "INSERT INTO autorizacion (no_empl, nombre, passw, area)
            VALUES ('$no_empl', '$username', '$passw', '$area')";

    if ($conexion->query($sql) === TRUE) {
        $_SESSION["usuario"] = $username;
        $_SESSION["no_empl"] = $no_empl;
        $_SESSION["area"] = $area;
        header("Location: autoritation.php"); // redirige después de registrar
        exit();
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}

if (isset($_POST["Entrar"])) {
    $sql = "SELECT * FROM autorizacion WHERE nombre = '$username' AND passw = '$passw'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $_SESSION["usuario"] = $username;
        $_SESSION["no_empl"] = $fila["no_empl"];
        $_SESSION["area"] = $fila["area"];
        header("Location: autoritation.php"); // redirige después de iniciar sesión
        exit();
    } else {
        echo "Nombre o contraseña incorrectos.";
    }
}
?>
