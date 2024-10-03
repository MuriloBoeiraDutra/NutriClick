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
        return ['status' => true, 'message' => null];
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

function get_consume_database($user_id, $data_ingestao) {
    global $pdo;

    $sql = "
        SELECT 
            f.id AS food_id,
            c.user_id,
            c.data_ingestao,
            f.nome,
            c.gramas AS consumed_gramas,
            (f.calorias * c.gramas / f.gramas) AS calorias,
            (f.carboidratos * c.gramas / f.gramas) AS carboidratos,
            (f.proteinas * c.gramas / f.gramas) AS proteinas,
            (f.gorduras * c.gramas / f.gramas) AS gorduras,
            c.id AS id_consume,
            c.meal_time
        FROM 
            consume c
        JOIN 
            food f ON c.food_id = f.id
        WHERE 
            c.user_id = :user_id AND 
            c.data_ingestao = :data_ingestao
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':data_ingestao', $data_ingestao, PDO::PARAM_STR);

    try {
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($response)) {
            return [
                'status' => true,
                'data' => $response,
                'message' => null
            ];
        } else {
            return [
                'status' => false,
                'data' => null,
                'message' => "Nenhum registro encontrado para o ID do usuário {$user_id} na data {$data_ingestao}."
            ];
        }
    } catch (\PDOException $e) {
        return [
            'status' => false,
            'data' => null,
            'message' => $e->getMessage()
        ];
    }
}

?>