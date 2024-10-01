<?php 
session_start();
require_once 'utils/imports.php';
require_once 'actions/consume_action.php';

$_PUT = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    die(json_encode(['status' => false, 'mensagem' => 'Método não permitido'], JSON_UNESCAPED_UNICODE));
}

if (isset($_PUT['id_user']) && isset($_PUT['id_food']) && isset($_PUT['gramas'])) {
    $resultado = put_consume($_PUT['id_user'], $_PUT['id_food'], $_PUT['gramas']);
} else {
    http_response_code(400);
    echo json_encode(['status' => false, 'mensagem' => 'Parâmetro inválido. Forneça um ID de usuário, ID de alimento e as gramas.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($resultado['status']) {
    http_response_code(200);
} else {
    http_response_code(404);
}

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
?>