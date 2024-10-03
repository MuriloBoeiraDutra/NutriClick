<?php
require_once "dao/foodDAO.php";

function get_food_by_name(){
    $termo = $_GET['nome'];

    if (!$termo) {
        return ["status" => false, "message" => "Este alimento não foi encontrado"];
    }

    return get_food_by_name_database($termo);
}

function get_food_by_id(){
    $id = $_GET['id'];

    if (!$id) {
        return ["status" => false, "message" => "O ID do alimento não existe"];
    }
    
    return get_food_by_id_database($id);
}

?>