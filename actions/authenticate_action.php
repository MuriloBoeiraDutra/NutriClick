<?php

function authenticate() {
    if (isset($_SESSION['authorized']) && isset($_SESSION['expired']) && $_SESSION['authorized'] && $_SESSION['expired'] > time()) {
        http_response_code(204);
        return ["status" => true, "message" => null];
    } else {
        http_response_code(403);
        session_destroy();
        $response = ["status" => false, "message" => "Sua sessão expirou ou você não está autenticado. Faça o login novamente otário kkk."];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        die();
    }
}

?>