<?php 
session_start();
require_once 'actions/login_action.php';

$_POST = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die();
}

$result = login_user();

if ($result['status'] === true) {
    $_SESSION['authorized'] = true;
    http_response_code(200);
} else {
    $_SESSION['authorized'] = false;
    http_response_code(403);
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>