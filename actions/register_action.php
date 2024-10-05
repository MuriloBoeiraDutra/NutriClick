<?php
require_once "dao/userDAO.php";

function register_user(){
    $username       = $_POST['username'] ?? null;
    $password       = $_POST['senha'] ?? null;
    $age            = $_POST['idade'] ?? null;
    $gender         = $_POST['genero'] ?? null;
    $height         = $_POST['altura'] ?? null;
    $weight         = $_POST['peso'] ?? null;
    $activity_level = $_POST['nivel_atividade'] ?? null;
    $email          = $_POST['email'] ?? null;
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ["status" => false, "message" => "O e-mail fornecido não é válido."];
    }

    if (!$username || !$password || !$height || !$weight || !$activity_level || !$email || !$age || !$gender) {
        return ["status" => false, "message" => "Todos os campos são obrigatórios."];
    }

    return register_user_database($username, $password, $height, $weight, $email, $activity_level, $age, $gender);
}

?>