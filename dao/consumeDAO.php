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
            return ['status' => true, 'message' => null];
        } else {
            return ['status' => false, 'message' => 'Nenhum registro encontrado para deletar.'];
        }
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

?>