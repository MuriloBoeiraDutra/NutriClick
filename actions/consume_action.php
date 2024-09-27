<?php 
require_once "dao/consumeDAO.php";

function register_consume() {
    $user_id = $_POST['user_id'] ?? null;
    $food_id = $_POST['food_id'] ?? null;
    $gramas = $_POST['gramas'] ?? null;
    $meal_time = $_POST['meal_time'] ?? null;

    $valid_meal_times = ['café da manhã', 'almoço', 'lanche', 'janta'];

    if ($user_id == null) {
        return ["result" => "Id do usuário não pode ser nulo!", "status" => false];
    }

    if ($food_id == null) {
        return ["result" => "Id do alimento não pode ser nulo!", "status" => false];
    }

    if ($meal_time == null) {
        return ["result" => "Momento da refeição não pode ser nula!", "status" => false];
    }

    $meal_time_lower = strtolower($meal_time);

    if (!in_array($meal_time_lower, array_map('strtolower', $valid_meal_times))) {
        return ["result" => "Momento da refeição inválido!", "status" => false];
    }

    return register_consume_database($user_id, $food_id, $meal_time, $gramas);
}

function delete_consume($requestBody) {
    $consume_id = $requestBody['id'] ?? null;

    if (!is_numeric($consume_id)) {
        return ["result" => "Id deve ser um número válido!", "status" => false];
    }

    if ($consume_id == null) {
        return ["result" => "O id não pode ser nulo!", "status" => false];
    }

    return delete_consume_database($consume_id);
}