<?php 
require_once "dao/db_connection.php";

function register_consume_database($user_id, $food_id, $meal_time, $gramas, $data_ingestao = null) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT criado_em FROM user WHERE id = :user_id");
    $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return ["status" => false, "message" => "Usuário não encontrado."];
    }

    $created_at = $user['criado_em'];
    $current_date = date('Y-m-d');
    $data_ingestao_to_insert = $data_ingestao ? $data_ingestao : $current_date;

    if ($data_ingestao_to_insert < $created_at) {
        return ["status" => false, "message" => "A data de ingestão não pode ser anterior à data de criação do usuário."];
    }

    $stmt = $pdo->prepare(
        "INSERT INTO consume (user_id, food_id, data_ingestao, meal_time, gramas) 
        VALUES (:user_id, :food_id, :data_ingestao, :meal_time, :gramas)"
    );
    $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);
    $stmt->bindValue(":food_id", $food_id, \PDO::PARAM_INT);
    $stmt->bindValue(":data_ingestao", $data_ingestao_to_insert, \PDO::PARAM_STR);
    $stmt->bindValue(":meal_time", $meal_time, \PDO::PARAM_STR);
    $stmt->bindValue(":gramas", $gramas, \PDO::PARAM_INT);
        
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
            c.gramas,
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

        foreach ($response as &$item) {
            $item['calorias'] = round($item['calorias'], 2);
            $item['carboidratos'] = round($item['carboidratos'], 2);
            $item['proteinas'] = round($item['proteinas'], 2);
            $item['gorduras'] = round($item['gorduras'], 2);
            $item['gramas'] = round($item['gramas'], 2);
        }

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

function update_consume_database($id, $gramas) {
    global $pdo;

    $sqlSelect = "
        SELECT f.gramas AS food_gramas, f.calorias, f.carboidratos, f.proteinas, f.gorduras
        FROM consume c
        JOIN food f ON c.food_id = f.id
        WHERE c.id = :id
    ";

    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->bindValue(':id', $id, PDO::PARAM_INT);

    try {
        $stmtSelect->execute();
        $foodData = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if (!$foodData) {
            return [
                'status' => false,
                'data' => null,
                'message' => 'Alimento não encontrado.'
            ];
        }

        $sqlUpdate = "
            UPDATE consume 
            SET gramas = :gramas
            WHERE id = :id
        ";

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':gramas', $gramas, PDO::PARAM_STR);
        $stmtUpdate->bindValue(':id', $id, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $calorias = ($foodData['calorias'] * $gramas) / $foodData['food_gramas'];
        $carboidratos = ($foodData['carboidratos'] * $gramas) / $foodData['food_gramas'];
        $proteinas = ($foodData['proteinas'] * $gramas) / $foodData['food_gramas'];
        $gorduras = ($foodData['gorduras'] * $gramas) / $foodData['food_gramas'];

        return [
            'status' => true,
            'data' => [
                'id' => $id,
                'gramas' => floatval($gramas),
                'calorias' => round($calorias, 2),
                'carboidratos' => round($carboidratos, 2),
                'proteinas' => round($proteinas, 2),
                'gorduras' => round($gorduras, 2),
            ],
            'message' => 'Gramas e macros atualizados com sucesso.'
        ];
    } catch (\PDOException $e) {
        return [
            'status' => false,
            'data' => null,
            'message' => "Erro ao atualizar: " . $e->getMessage()
        ];
    }
}

?>