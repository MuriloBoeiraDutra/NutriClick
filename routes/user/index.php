<?php
require_once 'actions/user_actions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    register_user();
}
?>

<!-- 
    GET -> /user/ Busca todos os usuários
    POST -> /user/ Cadastra um novo usuário
    GET -> /user/{id} Busca O usuário
    PUT -> /user/{id} Atualiza O usuário
    DELETE -> /user/{id} Deleta O usuário
-->