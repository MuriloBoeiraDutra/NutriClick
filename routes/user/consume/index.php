<?php 
require_once 'actions/consume_action.php';

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


/*CREATE TABLE nutriclick.consume (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    food_id INT,
    data_ingestao DATE DEFAULT CURRENT_DATE,  -- Armazena a data atual automaticamente
    meal_time ENUM('café da manhã', 'almoço', 'lanche', 'janta'),  -- Opções de refeição
    gramas DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (food_id) REFERENCES foods(id)
);*/

?>