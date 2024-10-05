<?php

function calculateTMB($weight, $height, $nivel_atividade, $gender, $age) {
    if ($gender === 'feminino') {
        $tmb = 10 * $weight + 6.25 * ($height * 100) - 5 * $age - 161;
    } else {
        $tmb = 10 * $weight + 6.25 * ($height * 100) - 5 * $age + 5;
    }

    switch ($nivel_atividade) {
        case 'sedentario':
            $tmb *= 1.2;
            break;
        case 'levemente ativo':
            $tmb *= 1.375;
            break;
        case 'ativo':
            $tmb *= 1.55;
            break;
        case 'muito ativo':
            $tmb *= 1.725;
            break;
        default:
            break;
    }

    return intval(round($tmb));
}

?> 