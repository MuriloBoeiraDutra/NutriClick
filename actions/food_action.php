<?php
require_once "dao/foodDAO.php";

function get_food_by_name(){
    $termo = $_GET['nome'];

    if (!$termo) {
        echo "Não foi encontrado esse alimento";
        return;
    }

    return get_food_by_name_database($termo);
}

function get_food_by_id(){
    $id = $_GET['id'];

    if (!$id) {
        echo "ID do alimento não existe.";
        return;
    }
    
    return get_food_by_id_database($id);
}

function put_values($food, $novasGramas) {
    if ($novasGramas < 0) {
        return [
            'error' => 'A quantidade de gramas não pode ser negativa.'
        ];
    }

    $fator = $novasGramas / $food['gramas'];

    return [
        'id' => $food['id'],
        'nome' => $food['nome'],
        'gramas' => $novasGramas,
        'calorias' => $food['calorias'] * $fator,
        'carboidratos' => $food['carboidratos'] * $fator,
        'proteinas' => $food['proteinas'] * $fator,
        'gorduras' => $food['gorduras'] * $fator,
    ];
}

?>