<?php 
require_once "dao/consumeDAO.php";

function register_consume() {
    $user_id = $_POST['user_id'] ?? null;
    $food_id = $_POST['food_id'] ?? null;
    $meal_time = $_POST['meal_time'] ?? null;

    if ($user_id == null) {
        return ["result" => "Id do usuário não pode ser nulo!", "status" => false];
    }

    if ($food_id == null) {
        return ["result" => "Id do alimento não pode ser nulo!", "status" => false];
    }

    if ($meal_time == null) {
        return ["result" => "Hora da refeição não pode ser nula!", "status" => false];
    }

    return register_consume_database($user_id, $food_id, $meal_time);
}

function delete_consume($requestBody) {
    $user_id = $requestBody['user_id'] ?? null;
    $food_id = $requestBody['food_id'] ?? null;
    $data_ingestao = $requestBody['data_ingestao'] ?? null;
    $meal_time = $requestBody['meal_time'] ?? null;

    if ($user_id == null) {
        return ["result" => "Id do usuário não pode ser nulo!", "status" => false];
    }

    if ($food_id == null) {
        return ["result" => "Id do alimento não pode ser nulo!", "status" => false];
    }

    if ($data_ingestao == null) {
        return ["result" => "A data da ingestão não pode ser nula!", "status" => false];
    }

    return delete_consume_database($user_id, $food_id, $data_ingestao, $meal_time);
}