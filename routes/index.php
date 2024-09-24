<?php
session_start();

if($_SESSION['authorized']){
    echo "deu bom";
} else {
    echo "ratio";
    http_response_code(403);
    die();
}

?>