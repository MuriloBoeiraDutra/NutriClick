<?php 

function cors() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
    header("Access-Control-Allow-Headers: x-access-token, Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Credentials: true");
    
    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        die();
    }
}

?>