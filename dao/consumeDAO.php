<?php 
require_once "dao/db_connection.php";

function register_consume_database($user_id, $food_id, $meal_time, $gramas) {
    global $pdo;
    
    $stmt = $pdo->prepare(
        "INSERT INTO consume (user_id, food_id, meal_time, gramas) 
        VALUES (:user_id, :food_id, :meal_time, :gramas)");
    $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);
    $stmt->bindValue(":food_id", $food_id, \PDO::PARAM_INT);
    $stmt->bindValue(":meal_time", $meal_time, \PDO::PARAM_STR);
    $stmt->bindValue(":gramas", $gramas, \PDO::PARAM_STR);
        
    try {
        $stmt->execute();
        $lastId = $pdo->lastInsertId();
        
        $stmt = $pdo->prepare("SELECT * FROM consume WHERE id = :id");
        $stmt->bindValue(":id", $lastId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $consume = $stmt->fetch(\PDO::FETCH_ASSOC);

        return [
            'status' => true,
            'data' => $consume,
            'message' => null
        ];
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

function delete_consume_database($id) {
    global $pdo;

    $stmt = $pdo->prepare('DELETE FROM consume WHERE id = :id LIMIT 1');

    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

    try {        
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['status' => true, 'message' => 'Consumo deletado com sucesso.'];
        } else {
            return ['status' => false, 'message' => 'Nenhum registro encontrado para deletar.'];
        }
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

function put_consume_and_calculate_macros_database($id_food, $gramas) {
    global $pdo;

    // Obtenha os dados do alimento
    $foodResult = get_food_by_id_database($id_food);
    
    if (!$foodResult['status']) {
        return ['status' => false, 'mensagem' => 'Alimento não encontrado.'];
    }

    $foodData = $foodResult['data'];

    // Calcule os macronutrientes baseados nas gramas
    $carbs = ($foodData['carboidratos'] / $foodData['gramas']) * $gramas;
    $proteins = ($foodData['proteinas'] / $foodData['gramas']) * $gramas;
    $fats = ($foodData['gorduras'] / $foodData['gramas']) * $gramas;

    // Retorne os macronutrientes calculados
    return [
        'status' => true,
        'dados' => [
            'carboidratos' => $carbs,
            'proteinas' => $proteins,
            'gorduras' => $fats,
        ],
        'mensagem' => 'Macronutrientes calculados com sucesso.'
    ];
}

?>