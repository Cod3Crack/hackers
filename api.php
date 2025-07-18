<?php
// api.php
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Obtener todas las herramientas
        $result = $conn->query("SELECT id, title, url FROM tools ORDER BY created_at DESC");
        $tools = [];
        while ($row = $result->fetch_assoc()) {
            $tools[] = $row;
        }
        echo json_encode($tools);
        break;

    case 'POST':
        // Crear una nueva herramienta
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $conn->real_escape_string($data['title']);
        $url = $conn->real_escape_string($data['url']);

        $sql = "INSERT INTO tools (title, url) VALUES ('$title', '$url')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear la herramienta: ' . $conn->error]);
        }
        break;

    case 'PUT':
        // Actualizar una herramienta existente
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($data['id']);
        $title = $conn->real_escape_string($data['title']);
        $url = $conn->real_escape_string($data['url']);

        $sql = "UPDATE tools SET title='$title', url='$url' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar la herramienta: ' . $conn->error]);
        }
        break;

    case 'DELETE':
        // Eliminar una herramienta
        $id = intval($_GET['id']);
        $sql = "DELETE FROM tools WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar la herramienta: ' . $conn->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

$conn->close();
?>