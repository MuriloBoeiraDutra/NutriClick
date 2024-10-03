<?php
require_once "dao/userDAO.php";

function register_user(){
    $username       = $_POST['username'];
    $password       = $_POST['senha'];
    $height         = $_POST['altura'];
    $weight         = $_POST['peso'];
    $activity_level = $_POST['nivel_atividade'];
    $email          = $_POST['email'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ["status" => false, "message" => "O e-mail fornecido não é válido."];
    }

    if (!$username || !$password || !$height || !$weight || !$activity_level || !$email) {
        return ["status" => false, "message" => "Todos os campos são obrigatórios."];
    }

    return register_user_database($username, $password, $height, $weight, $email, $activity_level);
}

?>