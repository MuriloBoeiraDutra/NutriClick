<?php
require_once "dao/userDAO.php";

function login_user() {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (!$email || !$password) {
        return ["status" => false, "message" => "Email ou senha não foram fornecidos."];
    }

    return login_user_database($email, $password);
}

?>