<?php

require "../Config/db.php";
require "../Clases/Usuario.php";

if(!isset($_GET['correo'])){
    exit;
}

$correo = trim($_GET['correo']);

$usuario = new Usuario($pdo);

if(
    $usuario->existeCorreo($correo)
){
    echo "✗ Correo ya registrado";
}
else{
    echo "✓ Correo disponible";
}