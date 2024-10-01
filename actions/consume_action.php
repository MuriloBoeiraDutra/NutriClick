<?php 
require_once "dao/consumeDAO.php";

function register_consume() {
    $user_id = $_POST['user_id'] ?? null;
    $food_id = $_POST['food_id'] ?? null;
    $gramas = $_POST['gramas'] ?? null;
    $meal_time = $_POST['meal_time'] ?? null;

    $valid_meal_times = ['café da manhã', 'almoço', 'lanche', 'janta'];

    if ($user_id == null || $user_id < 0 || !is_numeric($user_id)) {
        return ["result" => "Id do usuário não pode ser nulo!", "status" => false];
    }

    if ($food_id == null || $food_id < 0 || !is_numeric($food_id)) {
        return ["result" => "Id do alimento não pode ser nulo!", "status" => false];
    }

    if ($meal_time == null) {
        return ["result" => "Momento da refeição não pode ser nula!", "status" => false];
    }

    if ($gramas == null || $gramas < 0 || !is_numeric($gramas)) {
        return ["result" => "As gramas não podem ser nulas", "status" => false];
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

function put_consume($id_user, $id_food, $gramas) {
    global $pdo;

    // Primeiro, obtenha o ID do consumo existente
    $stmt = $pdo->prepare("SELECT id FROM consume WHERE user_id = :id_user AND food_id = :id_food");
    $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
    $stmt->bindValue(":id_food", $id_food, PDO::PARAM_INT);
    $stmt->execute();
    
    $consumeData = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if (!$consumeData) {
        return ['status' => false, 'mensagem' => 'Consumo não encontrado.'];
    }

    $consume_id = $consumeData['id'];

    // Atualize as gramas na tabela consume
    $stmt = $pdo->prepare("UPDATE consume SET gramas = :gramas WHERE id = :consume_id");
    $stmt->bindValue(":gramas", $gramas, PDO::PARAM_INT);
    $stmt->bindValue(":consume_id", $consume_id, PDO::PARAM_INT);

    try {
        $stmt->execute();

        // Chame a função para calcular os macronutrientes
        $resultadoMacros = put_consume_and_calculate_macros_database($id_food, $gramas);

        if (!$resultadoMacros['status']) {
            return ['status' => false, 'mensagem' => 'Erro ao calcular os macronutrientes.'];
        }

        // Retorne os dados do consumo atualizado
        return [
            'status' => true,
            'dados' => [
                'id_consume' => $consume_id,
                'id_user' => $id_user,
                'id_food' => $id_food,
                'gramas' => $gramas,
                'carboidratos' => $resultadoMacros['dados']['carboidratos'],
                'proteinas' => $resultadoMacros['dados']['proteinas'],
                'gorduras' => $resultadoMacros['dados']['gorduras'],
            ],
            'mensagem' => 'Consumo atualizado com sucesso.'
        ];
    } catch (\PDOException $e) {
        return ['status' => false, 'mensagem' => $e->getMessage()];
    }
}
