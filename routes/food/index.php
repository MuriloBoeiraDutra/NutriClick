<?php 
session_start();
require_once 'utils/imports.php';
require_once 'actions/food_action.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die();
}

if (isset($_GET['nome'])) {
    $result = get_food_by_name();
} else {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Parâmetro inválido. Forneça um nome ou ID.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($result['status']) {
    http_response_code(200);
} else {
    http_response_code(404);
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>