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
        return ["result" => "O e-mail fornecido não é válido.", "status" => false];
    }

    if (!$username || !$password || !$height || !$weight || !$activity_level || !$email) {
        return ["result" => "Todos os campos são obrigatórios.", "status" => false] ;
    }

    return register_user_database($username, $password, $height, $weight, $email, $activity_level);
}

?>