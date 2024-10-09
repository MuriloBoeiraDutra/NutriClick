<?php 
require_once "dao/consumeDAO.php";

function register_consume() {
    if (!isset($_SESSION['user_id'])) {
        return ["status" => false, "message" => "Usuário não está autenticado."];
    }
    
    $loggedUserId       = $_SESSION['user_id'];
    $food_id            = $_POST['food_id'] ?? null;
    $gramas             = $_POST['gramas'] ?? null;
    $meal_time          = $_POST['meal_time'] ?? null;
    $data_ingestao      = $_POST['data_ingestao'] ?? null;

    $valid_meal_times = ['café da manhã', 'almoço', 'lanche', 'janta'];

    if ($loggedUserId == null || $loggedUserId < 0 || !is_numeric($loggedUserId)) {
        return ["status" => false, "message" => "Id do usuário não pode ser nulo!"];
    }

    if ($food_id == null || $food_id < 0 || !is_numeric($food_id)) {
        return ["status" => false, "message" => "Id do alimento não pode ser nulo!"];
    }

    if ($meal_time == null) {
        return ["status" => false, "message" => "Momento da refeição não pode ser nula!"];
    }

    if ($gramas === null) {
        return ["status" => false, "message" => "As gramas não podem ser nulas"];
    }
    
    if (!is_numeric($gramas)) {
        return ["status" => false, "message" => "As gramas devem ser um número"];
    }
    
    if ($gramas < 0) {
        return ["status" => false, "message" => "As gramas não podem ser negativas"];
    }

    $meal_time_lower = strtolower($meal_time);

    if (!in_array($meal_time_lower, array_map('strtolower', $valid_meal_times))) {
        return ["status" => false, "message" => "Momento da refeição inválido!"];
    }

    return register_consume_database($loggedUserId, $food_id, $meal_time, $gramas, $data_ingestao);
}

function delete_consume($requestBody) {
    $consume_id = $requestBody['id'] ?? null;

    if (!is_numeric($consume_id)) {
        return ["status" => false, "message" => "Id deve ser um número válido!"];
    }

    if ($consume_id == null) {
        return ["status" => false, "message" => "O id não pode ser nulo!"];
    }

    return delete_consume_database($consume_id);
}

function get_consume() {
    if (!isset($_SESSION['user_id'])) {
        return ["status" => false, "message" => "Usuário não está autenticado."];
    }
    
    $loggedUserId  = $_SESSION['user_id'];
    $data_ingestao = $_GET['data_ingestao'] ?? null;

    if (!$loggedUserId || !is_numeric($loggedUserId)) {
        return ["status" => false, "message" => "Id do usuário não pode ser nulo!"];
    }

    if (!$data_ingestao) {
        return ["status" => false, "message" => "A data informada não é válida"];
    }

    return get_consume_database($loggedUserId, $data_ingestao);
}

function edit_consume($requestBody) {
    $id = $requestBody['id'] ?? null;
    $gramas = $requestBody['gramas'] ?? null;

    if (!$id || !$gramas) {
        return ["status" => false, "message" => "Parâmetros insuficientes."];
    }

    if (!is_numeric($gramas)) {
        return ["status" => false, "message" => "As gramas devem ser um número"];
    }
    
    if ($gramas < 0) {
        return ["status" => false, "message" => "As gramas não podem ser negativas"];
    }

    return update_consume_database($id, $gramas);
}

?>