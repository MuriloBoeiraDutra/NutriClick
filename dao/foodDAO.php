<?php 
require_once "dao/db_connection.php";

function get_food_by_name_database($termo) {
    global $pdo;
    
    $termo = "%$termo%";
    $stmt = $pdo->prepare("SELECT * FROM food WHERE nome LIKE :termo");
    $stmt->bindParam(':termo', $termo);
    try{
        $stmt->execute();
        $food = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($food){
            return [
                'status' => true,
                'data' => $food,
                'message' => null
            ];
            
        } else {
            return [
                'status' => false,
                'data' => [],
                'message' => 'Alimento não encontrado'
            ];
        }
    } catch (\PDOException $e) {
        return [
            'status' => false,
            'data' => [],
            'message' => $e->getMessage()
        ];
    }

}

function get_food_by_id_database($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM food WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    try {
        $stmt->execute();
        $food = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($food) {
            return [
                'status' => true,
                'data' => $food,
                'message' => null
            ];
        } else {
            return [
                'status' => false,
                'data' => null,
                'message' => 'Alimento não encontrado'
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


