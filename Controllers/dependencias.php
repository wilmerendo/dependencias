<?php

require_once(__DIR__ . "/../Configs/connection.php");

header('Content-Type: application/json');

/**
 * Controller for handling CRUD operations for Dependencias
 */

try {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST' && isset($_POST['_method'])) {
        $method = strtoupper($_POST['_method']);
    }

    switch ($method) {
        case 'GET':
            handleGetRequest();
            break;
        case 'POST':
            handlePostRequest();
            break;
        case 'PUT':
            handlePutRequest();
            break;
        case 'DELETE':
            handleDeleteRequest();
            break;
        default:
            throw new Exception("Unsupported request method.");
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

function handleGetRequest()
{
    global $conn;
    if (isset($_GET['id'])) {
        $id = sanitizeInput($_GET['id']);
        $sql = "SELECT * FROM dependencias WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dependencia = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $dependencia]);
    } else {
        $sql = "SELECT d.*, e.nombre_empresa FROM dependencias d JOIN empresas e ON d.empresas_id = e.id";
        $result = $conn->query($sql);
        $dependencias = [];
        while ($row = $result->fetch_assoc()) {
            $dependencias[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $dependencias]);
    }
}

function handlePostRequest()
{
    global $conn;
    $data = $_POST;
    $cod_dependencia = sanitizeInput($data['cod_dependencia']);
    $nombre_dependencia = sanitizeInput($data['nombre_dependencia']);
    $telefono = sanitizeInput($data['telefono']);
    $direccion = sanitizeInput($data['direccion']);
    $estado = isset($data['estado']) ? 1 : 0;
    $empresas_id = sanitizeInput($data['empresas_id']);

    $sql = "INSERT INTO dependencias (cod_dependencia, nombre_dependencia, telefono, direccion, estado, empresas_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $cod_dependencia, $nombre_dependencia, $telefono, $direccion, $estado, $empresas_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Dependencia creada correctamente']);
    } else {
        throw new Exception("Error al crear la dependencia: " . $stmt->error);
    }
}

function handlePutRequest()
{
    global $conn;

    parse_str(file_get_contents("php://input"), $putData);

    if (!isset($putData['id'])) {
        throw new Exception("ID no proporcionado para actualizar la dependencia.");
    }

    $id = sanitizeInput($putData['id']);
    $cod_dependencia = sanitizeInput($putData['cod_dependencia']);
    $nombre_dependencia = sanitizeInput($putData['nombre_dependencia']);
    $telefono = sanitizeInput($putData['telefono']);
    $direccion = sanitizeInput($putData['direccion']);
    $estado = isset($putData['estado']) ? 1 : 0;
    $empresas_id = sanitizeInput($putData['empresas_id']);

    $sql = "UPDATE dependencias SET cod_dependencia = ?, nombre_dependencia = ?, telefono = ?, direccion = ?, estado = ?, empresas_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiii", $cod_dependencia, $nombre_dependencia, $telefono, $direccion, $estado, $empresas_id, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Dependencia actualizada correctamente']);
    } else {
        throw new Exception("Error al actualizar la dependencia: " . $stmt->error);
    }
}

function handleDeleteRequest()
{
    global $conn;
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['id'])) {
        throw new Exception("ID no proporcionado para eliminar la dependencia.");
    }
    $id = sanitizeInput($input['id']);

    $sql = "DELETE FROM dependencias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Dependencia eliminada correctamente']);
    } else {
        throw new Exception("Error al eliminar la dependencia: " . $stmt->error);
    }
}

function sanitizeInput($input)
{
    global $conn;
    if (is_null($input)) {
        return '';
    }
    return $conn->real_escape_string(trim($input));
}