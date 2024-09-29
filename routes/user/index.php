<?php
session_start();
require_once 'actions/user_actions.php';
require_once 'utils/imports.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    register_user();
}

?>