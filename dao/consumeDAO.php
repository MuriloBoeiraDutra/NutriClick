<?php 
require_once "dao/db_connection.php";

function register_consume_database($user_id, $food_id, $meal_time) {
    global $pdo;
    
    $stmt = $pdo->prepare(
        "INSERT INTO consume (user_id, food_id, meal_time) 
        VALUES (:user_id, :food_id, :meal_time)");
    $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_STR);
    $stmt->bindValue(":food_id", $food_id, \PDO::PARAM_STR);
    $stmt->bindValue(":meal_time", $meal_time, \PDO::PARAM_STR);
        
    try {
        $stmt->execute();
        return ['status' => true, 'message' => null];
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }

}

function delete_consume_database($user_id, $food_id, $data_ingestao, $meal_time) {
    global $pdo;

    $stmt = $pdo->prepare('DELETE FROM consume 
        WHERE user_id = :user_id 
        AND food_id = :food_id 
        AND DATE(data_ingestao) = DATE(:data_ingestao) 
        AND (:meal_time IS NULL OR meal_time = :meal_time)');

    $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_STR);
    $stmt->bindParam(':food_id', $food_id, \PDO::PARAM_STR);
    $stmt->bindParam(':data_ingestao', $data_ingestao, \PDO::PARAM_STR);
    $stmt->bindParam(':meal_time', $meal_time, \PDO::PARAM_STR);

    try {
        $stmt->execute();
        return ['status' => true, 'message' => null];
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

?>