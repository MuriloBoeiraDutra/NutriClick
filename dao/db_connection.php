<?php
    $database = "mysql:host=localhost;dbname=nutriclick";
    $root = "root";
    $db_password = "";
    try{
        $pdo = new PDO($database, $root, $db_password);
    }
    catch(PDOException $e){
        die("Ocorreu um erro ao conectar no banco de dados: " . $e->getMessage());
    }
    catch(Exception $e){
        die("Ocorreu um erro generico: " . $e->getMessage());
    }
?>
