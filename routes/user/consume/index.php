<?php 
/*session_start();
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

*/?>

<?php 
session_start();
require_once 'actions/consume_action.php';
require_once 'utils/imports.php';

$requestBody = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die(json_encode(['status' => false, 'message' => 'Método não permitido'], JSON_UNESCAPED_UNICODE));
}

$result = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = $requestBody;
    $result = register_consume();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $result = delete_consume($requestBody);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_GET['user_id'] ?? null;
    $data_ingestao = $_GET['data_ingestao'] ?? null;

    if (!$user_id || !$data_ingestao) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parâmetros insuficientes.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $result = get_consume_database($user_id, $data_ingestao);
}

if ($result['status'] === true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(201);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        http_response_code(204);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        http_response_code(200);
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(400);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        http_response_code(404);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        http_response_code(404);
    }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>