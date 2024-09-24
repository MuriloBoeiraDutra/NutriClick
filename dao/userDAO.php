<?php 
require_once "dao/db_connection.php";

function register_user_database($username, $password, $height, $weight, $email, $activity_level) {
    global $pdo;
    
    $stmt = $pdo->prepare(
        "INSERT INTO user (username, senha, altura, peso, nivel_atividade, email) 
        VALUES (:username, :senha, :altura, :peso, :nivel_atividade, :email)");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bindValue(":username", $username, \PDO::PARAM_STR);
    $stmt->bindValue(":senha", $hashed_password, \PDO::PARAM_STR);
    $stmt->bindValue(":altura", $height, \PDO::PARAM_STR);
    $stmt->bindValue(":peso", $weight, \PDO::PARAM_STR);
    $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
    $stmt->bindValue(":nivel_atividade", $activity_level, \PDO::PARAM_STR);
        
    try {
        $stmt->execute();
        return ['status' => true, 'message' => null];
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }

}

function login_user_database($email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT senha FROM user WHERE email = :email");
    $stmt->bindValue(":email", $email, \PDO::PARAM_STR);

    try {
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $hashed_password = $user['senha'];
            if (password_verify($password, $hashed_password)) {
                return ['status' => true, 'message' => null];
            } else {
                return ['status' => false, 'message' => 'Senha incorreta'];
            }
        } else {
            return ['status' => false, 'message' => 'Usuário não encontrado'];
        }
    } catch (\PDOException $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

?>