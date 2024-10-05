<?php
require_once "dao/foodDAO.php";

function get_food_by_name(){
    $termo = $_GET['nome'];

    if (!$termo) {
        return ["status" => false, "message" => "Este alimento não foi encontrado"];
    }

    return get_food_by_name_database($termo);
}

?>