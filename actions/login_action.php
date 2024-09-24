<?php
require_once "dao/userDAO.php";

function login_user() {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (!$email || !$password) {
        echo "Email ou senha não foram fornecidos.";
        return;
    }

    return login_user_database($email, $password);
}

?>