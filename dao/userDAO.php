<?php 
require_once "dao/db_connection.php";
require_once "utils/TMB.php";

function register_user_database($username, $password, $height, $weight, $email, $activity_level, $age, $gender) {
    global $pdo;
    
    $stmt = $pdo->prepare(
        "INSERT INTO user (username, senha, altura, peso, nivel_atividade, email, idade, genero) 
        VALUES (:username, :senha, :altura, :peso, :nivel_atividade, :email, :idade, :genero)");
    
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bindValue(":username", $username, \PDO::PARAM_STR);
    $stmt->bindValue(":senha", $hashed_password, \PDO::PARAM_STR);
    $stmt->bindValue(":altura", $height, \PDO::PARAM_STR);
    $stmt->bindValue(":peso", $weight, \PDO::PARAM_STR);
    $stmt->bindValue(":nivel_atividade", $activity_level, \PDO::PARAM_STR);
    $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
    $stmt->bindValue(":idade", $age, \PDO::PARAM_INT);
    $stmt->bindValue(":genero", $gender, \PDO::PARAM_STR);
        
    try {
        $stmt->execute();
        return ['status' => true, 'message' => null];
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}


function login_user_database($email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, username, altura, peso, nivel_atividade, senha, genero, idade FROM user WHERE email = :email");

    $stmt->bindValue(":email", $email, \PDO::PARAM_STR);

    try {
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $hashed_password = $user['senha'];
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user['id'];
                unset($user['senha']);
                $tmb = calculateTMB($user['peso'], $user['altura'], $user['nivel_atividade'], $user['genero'], $user['idade']);
                $user['taxa_metabolica_basal'] = $tmb;

                return [
                    'status' => true,
                    'data' => $user,
                    'message' => null
                ];
            } else {
                return ['status' => false, 'message' => 'Senha incorreta'];
            }
        } else {
            return ['status' => false, 'message' => 'Usuário não encontrado'];
        }
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => 'Erro na conexão: ' . $e->getMessage()];
    }
}

?>