<?php

session_start();

require "../Config/db.php";
require "../Clases/Usuario.php";
require "../vendor/autoload.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

/*
|--------------------------------------------------------------------------
| Verificar sesión temporal
|--------------------------------------------------------------------------
*/

if(
    !isset($_SESSION['pendiente_2fa']) ||
    !isset($_SESSION['correo'])
){
    die("Acceso no autorizado");
}

/*
|--------------------------------------------------------------------------
| Obtener código ingresado
|--------------------------------------------------------------------------
*/

$codigo = trim($_POST['codigo']);

$usuario = new Usuario($pdo);

$datos = $usuario->obtenerPorCorreo(
    $_SESSION['correo']
);

/*
|--------------------------------------------------------------------------
| Verificar que exista el usuario
|--------------------------------------------------------------------------
*/

if(!$datos){

    die("Usuario no encontrado");
}

/*
|--------------------------------------------------------------------------
| Validar código 2FA
|--------------------------------------------------------------------------
*/

$g = new GoogleAuthenticator();

if(
    $g->checkCode(
        $datos['secret_2fa'],
        $codigo
    )
){

    /*
    |--------------------------------------------------------------------------
    | Registrar acceso exitoso
    |--------------------------------------------------------------------------
    */

    $usuario->registrarAuditoria(
        $_SESSION['correo'],
        "success"
    );

    /*
    |--------------------------------------------------------------------------
    | Crear sesión definitiva
    |--------------------------------------------------------------------------
    */

    $_SESSION['autenticado'] = true;

    unset($_SESSION['pendiente_2fa']);

    header("Location: ../Views/Dashboard.php");
    exit;

}else{

    /*
    |--------------------------------------------------------------------------
    | Registrar intento fallido
    |--------------------------------------------------------------------------
    */

    $usuario->registrarAuditoria(
        $_SESSION['correo'],
        "fail"
    );

    die("Código incorrecto");
}