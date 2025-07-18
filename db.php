<?php
// db.php
header("Content-Type: application/json");

$servername = "sql311.infinityfree.com"; // o tu servidor de base de datos
$username = "if0_39498027";        // tu usuario de base de datos
$password = "orncA8ZW4ilTX";            // tu contraseña de base de datos
$dbname = "if0_39498027_hackers";    // el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
    die();
}
?>