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

?>


