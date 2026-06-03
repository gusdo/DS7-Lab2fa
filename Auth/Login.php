<?php

session_start();

require "../Config/db.php";
require "../Clases/Usuario.php";

/*
|--------------------------------------------------------------------------
| Validar Token CSRF
|--------------------------------------------------------------------------
*/

if(
    !isset($_POST['token']) ||
    !isset($_SESSION['token']) ||
    $_POST['token'] !== $_SESSION['token']
){
    die("Token CSRF inválido");
}

/*
|--------------------------------------------------------------------------
| Obtener datos del formulario
|--------------------------------------------------------------------------
*/

$correo = trim($_POST['correo']);
$password = $_POST['password'];

$usuario = new Usuario($pdo);

$datos = $usuario->obtenerPorCorreo($correo);

/*
|--------------------------------------------------------------------------
| Verificar usuario
|--------------------------------------------------------------------------
*/

if(!$datos){

    $usuario->registrarAuditoria(
        $correo,
        "fail"
    );

    die("Usuario no encontrado");
}

/*
|--------------------------------------------------------------------------
| Verificar contraseña
|--------------------------------------------------------------------------
*/

if(!password_verify($password, $datos['password'])){

    $usuario->registrarAuditoria(
        $correo,
        "fail"
    );

    die("Contraseña incorrecta");
}

/*
|--------------------------------------------------------------------------
| Crear sesión temporal para 2FA
|--------------------------------------------------------------------------
*/

$_SESSION['usuario_id'] = $datos['id'];

$_SESSION['correo'] = $datos['correo'];

$_SESSION['pendiente_2fa'] = true;

/*
|--------------------------------------------------------------------------
| Redirigir a verificación 2FA
|--------------------------------------------------------------------------
*/

header("Location: ../Views/Codigo_2fa.php");
exit;