<?php

require_once(__DIR__ . "/../Configs/connection.php");

header('Content-Type: application/json');

/**
 * Controller for handling CRUD operations for Empresas
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
        $sql = "SELECT * FROM empresas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $empresa = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $empresa]);
    } else {
        $sql = "SELECT * FROM empresas";
        $result = $conn->query($sql);
        $empresas = [];
        while ($row = $result->fetch_assoc()) {
            $empresas[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $empresas]);
    }
}

function handlePostRequest()
{
    global $conn;
    $data = $_POST;
    $nombre_empresa = sanitizeInput($data['nombre_empresa']);
    $nit = sanitizeInput($data['nit']);
    $correo = sanitizeInput($data['correo']);
    $telefono = sanitizeInput($data['telefono']);
    $direccion = sanitizeInput($data['direccion']);
    $nombre_representante_legal = sanitizeInput($data['nombre_representante_legal']);
    $contacto_representante_legal = sanitizeInput($data['contacto_representante_legal']);
    $correo_representante_legal = sanitizeInput($data['correo_representante_legal']);
    $estado = isset($data['estado']) ? 1 : 0;

    $sql = "INSERT INTO empresas (nombre_empresa, nit, correo, telefono, direccion, nombre_representante_legal, contacto_representante_legal, correo_representante_legal, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $nombre_empresa, $nit, $correo, $telefono, $direccion, $nombre_representante_legal, $contacto_representante_legal, $correo_representante_legal, $estado);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Empresa creada correctamente']);
    } else {
        throw new Exception("Error al crear la empresa: " . $stmt->error);
    }
}

function handlePutRequest()
{
    global $conn;

    parse_str(file_get_contents("php://input"), $putData);

    if (!isset($putData['id'])) {
        throw new Exception("ID no proporcionado para actualizar la empresa.");
    }

    $id = sanitizeInput($putData['id']);
    $nombre_empresa = sanitizeInput($putData['nombre_empresa']);
    $nit = sanitizeInput($putData['nit']);
    $correo = sanitizeInput($putData['correo']);
    $telefono = sanitizeInput($putData['telefono']);
    $direccion = sanitizeInput($putData['direccion']);
    $nombre_representante_legal = sanitizeInput($putData['nombre_representante_legal']);
    $contacto_representante_legal = sanitizeInput($putData['contacto_representante_legal']);
    $correo_representante_legal = sanitizeInput($putData['correo_representante_legal']);
    $estado = isset($putData['estado']) ? 1 : 0;

    $sql = "UPDATE empresas SET nombre_empresa = ?, nit = ?, correo = ?, telefono = ?, direccion = ?, nombre_representante_legal = ?, contacto_representante_legal = ?, correo_representante_legal = ?, estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssii", $nombre_empresa, $nit, $correo, $telefono, $direccion, $nombre_representante_legal, $contacto_representante_legal, $correo_representante_legal, $estado, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Empresa actualizada correctamente']);
    } else {
        throw new Exception("Error al actualizar la empresa: " . $stmt->error);
    }
}

function handleDeleteRequest()
{
    global $conn;
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['id'])) {
        throw new Exception("ID no proporcionado para eliminar la empresa.");
    }
    $id = sanitizeInput($input['id']);

    $sql = "DELETE FROM empresas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Empresa eliminada correctamente']);
    } else {
        throw new Exception("Error al eliminar la empresa: " . $stmt->error);
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