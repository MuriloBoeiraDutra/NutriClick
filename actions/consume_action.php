<?php 
require_once "dao/consumeDAO.php";

function register_consume() {
    $user_id = $_POST['user_id'] ?? null;
    $food_id = $_POST['food_id'] ?? null;
    $gramas = $_POST['gramas'] ?? null;
    $meal_time = $_POST['meal_time'] ?? null;

    $valid_meal_times = ['café da manhã', 'almoço', 'lanche', 'janta'];

    if ($user_id == null || $user_id < 0 || !is_numeric($user_id)) {
        return ["status" => false, "message" => "Id do usuário não pode ser nulo!"];
    }

    if ($food_id == null || $food_id < 0 || !is_numeric($food_id)) {
        return ["status" => false, "message" => "Id do alimento não pode ser nulo!"];
    }

    if ($meal_time == null) {
        return ["status" => false, "message" => "Momento da refeição não pode ser nula!"];
    }

    if ($gramas == null || $gramas < 0 || !is_numeric($gramas)) {
        return ["status" => false, "message" => "As gramas não podem ser nulas"];
    }

    $meal_time_lower = strtolower($meal_time);

    if (!in_array($meal_time_lower, array_map('strtolower', $valid_meal_times))) {
        return ["status" => false, "message" => "Momento da refeição inválido!"];
    }

    return register_consume_database($user_id, $food_id, $meal_time, $gramas);
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

function get_consume($requestBody) {
    $user_id = $_GET['user_id'] ?? null;
    $data_ingestao = $_GET['data_ingestao'] ?? null;

    if (!$user_id || !is_numeric($user_id)) {
        return ["status" => false, "message" => "Id do usuário não pode ser nulo!"];
    }

    if (!$data_ingestao) {
        return ["status" => false, "message" => "A data informada não é válida"];
    }

    return get_consume_database($user_id, $data_ingestao);
}

function edit_consume($requestBody) {
    $id = $requestBody['id'] ?? null;
    $gramas = $requestBody['gramas'] ?? null;

    if (!$id || !$gramas) {
        return ["status" => false, "message" => "Parâmetros insuficientes."];
    }

    return update_consume_database($id, $gramas);
}

?>