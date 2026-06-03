<?php

$host = "localhost";
$db = "laboratorio_2fa";
$user = "appuser";
$pass = "123456";

try{

    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

}catch(PDOException $e){

    die($e->getMessage());
}