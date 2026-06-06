<?php

session_start();

require "../Config/db.php";
require "../Clases/Usuario.php";
require "../vendor/autoload.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if(
    !isset($_SESSION['pendiente_2fa']) ||
    !isset($_SESSION['correo'])
){
    die("Acceso no autorizado");
}

$codigo = trim($_POST['codigo']);

$ip = $_SERVER['REMOTE_ADDR'];

$usuario = new Usuario($pdo);

$datos = $usuario->obtenerPorCorreo(
    $_SESSION['correo']
);

if(!$datos){
    die("Usuario no encontrado");
}

$g = new GoogleAuthenticator();

if(
    $g->checkCode(
        $datos['secret_2fa'],
        $codigo
    )
){

    $usuario->registrarAuditoria(
        $_SESSION['correo'],
        "success",
        $ip
    );

    $_SESSION['autenticado'] = true;

    unset($_SESSION['pendiente_2fa']);

    header("Location: ../Views/Dashboard.php");
    exit;

}else{

    $usuario->registrarAuditoria(
        $_SESSION['correo'],
        "fail",
        $ip
    );

    die("Código incorrecto");
}