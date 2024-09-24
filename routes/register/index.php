<?php 

require_once 'actions/register_action.php';

$_POST = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die();
}

$result = register_user();

if ($result['status'] === true) {
    http_response_code(201);
} else {
    http_response_code(500);
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>