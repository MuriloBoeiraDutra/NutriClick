<?php
    require_once 'utils/cors.php';
    require_once "actions/authenticate_action.php";

    cors();
    authenticate();
    
?>