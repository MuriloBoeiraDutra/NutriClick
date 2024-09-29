<?php 
session_start();
require_once 'actions/consume_action.php';
require_once 'utils/imports.php';

$requestBody = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    die(json_encode(['status' => false, 'message' => 'Método não permitido'], JSON_UNESCAPED_UNICODE));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = $requestBody;
    $result = register_consume();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $result = delete_consume($requestBody);
}

if ($result['status'] === true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(201);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        http_response_code(204);
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(400);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        http_response_code(404);
    }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>