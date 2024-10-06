<?php 
session_set_cookie_params([
    "secure" => true,
    "samesite" => "none"
]);

session_start();
require_once 'actions/consume_action.php';
require_once 'utils/imports.php';

$requestBody = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    die(json_encode(['status' => false, 'message' => 'Método não permitido'], JSON_UNESCAPED_UNICODE));
}

$result = [];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $_POST = $requestBody;
        $result = register_consume();
        break;

    case 'DELETE':
        $result = delete_consume($requestBody);
        break;

    case 'GET':
        $result = get_consume($requestBody);
        break;

    case 'PUT':
        $result = edit_consume($requestBody);
        break;
}

if ($result['status'] === true) {
    http_response_code(match ($_SERVER['REQUEST_METHOD']) {
        'POST' => 201,
        'DELETE' => 204,
        'GET' => 200,
        'PUT' => 200,
        default => 200
    });
} else {
    http_response_code(match ($_SERVER['REQUEST_METHOD']) {
        'POST' => 400,
        'DELETE' => 404,
        'GET' => 404,
        'PUT' => 400,
        default => 400
    });
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>