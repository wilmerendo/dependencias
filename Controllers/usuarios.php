<?php

require_once(__DIR__ . "/../Configs/connection.php");

header('Content-Type: application/json');

/**
 * Controller for handling CRUD operations for Usuarios (Colaboradores)
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
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

function handleGetRequest()
{
    global $conn;
    try {
        if (isset($_GET['id'])) {
            $id = sanitizeInput($_GET['id']);
            $sql = "SELECT * FROM colaboradores WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();

            if (!$usuario) {
                throw new Exception("Usuario no encontrado");
            }

            echo json_encode(['status' => 'success', 'data' => $usuario]);
        } else {
            $sql = "SELECT c.*, e.nombre_empresa, d.nombre_dependencia 
                    FROM colaboradores c 
                    JOIN empresas e ON c.empresas_id = e.id 
                    JOIN dependencias d ON c.dependencias_id = d.id
                    ORDER BY c.id DESC";
            $result = $conn->query($sql);

            if (!$result) {
                throw new Exception("Error al obtener usuarios: " . $conn->error);
            }

            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            echo json_encode(['status' => 'success', 'data' => $usuarios]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function handlePostRequest()
{
    global $conn;
    try {
        $data = $_POST;
        validateUserData($data);

        $nombres = sanitizeInput($data['nombres']);
        $apellidos = sanitizeInput($data['apellidos']);
        $correo = sanitizeInput($data['correo']);
        $telefono = sanitizeInput($data['telefono']);
        $estado = isset($data['estado']) ? 1 : 0;
        $empresas_id = sanitizeInput($data['empresas_id']);
        $dependencias_id = sanitizeInput($data['dependencias_id']);

        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT id FROM colaboradores WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("El correo ya está registrado");
        }

        $sql = "INSERT INTO colaboradores (nombres, apellidos, correo, telefono, estado, empresas_id, dependencias_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiii", $nombres, $apellidos, $correo, $telefono, $estado, $empresas_id, $dependencias_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al crear el usuario: " . $stmt->error);
        }

        echo json_encode(['status' => 'success', 'message' => 'Usuario creado correctamente']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function handlePutRequest()
{
    global $conn;
    try {
        parse_str(file_get_contents("php://input"), $putData);
        validateUserData($putData);

        if (!isset($putData['id'])) {
            throw new Exception("ID no proporcionado para actualizar el usuario.");
        }

        $id = sanitizeInput($putData['id']);
        $nombres = sanitizeInput($putData['nombres']);
        $apellidos = sanitizeInput($putData['apellidos']);
        $correo = sanitizeInput($putData['correo']);
        $telefono = sanitizeInput($putData['telefono']);
        $estado = isset($putData['estado']) ? 1 : 0;
        $empresas_id = sanitizeInput($putData['empresas_id']);
        $dependencias_id = sanitizeInput($putData['dependencias_id']);

        // Verificar si el correo ya existe para otro usuario
        $stmt = $conn->prepare("SELECT id FROM colaboradores WHERE correo = ? AND id != ?");
        $stmt->bind_param("si", $correo, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("El correo ya está registrado para otro usuario");
        }

        $sql = "UPDATE colaboradores 
                SET nombres = ?, apellidos = ?, correo = ?, telefono = ?, 
                    estado = ?, empresas_id = ?, dependencias_id = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiiis", $nombres, $apellidos, $correo, $telefono, $estado, $empresas_id, $dependencias_id, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el usuario: " . $stmt->error);
        }

        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function handleDeleteRequest()
{
    global $conn;
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id'])) {
            throw new Exception("ID no proporcionado para eliminar el usuario.");
        }

        $id = sanitizeInput($input['id']);

        $sql = "DELETE FROM colaboradores WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el usuario: " . $stmt->error);
        }

        echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function validateUserData($data)
{
    $required_fields = ['nombres', 'apellidos', 'correo', 'empresas_id', 'dependencias_id'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception("El campo {$field} es requerido.");
        }
    }

    if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo electrónico no es válido.");
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