<?php

session_start();

require "../Config/db.php";
require "../Clases/Usuario.php";

if(
    !isset($_POST['token']) ||
    !isset($_SESSION['token']) ||
    $_POST['token'] !== $_SESSION['token']
){
    mostrarError("Token CSRF inválido");
}

$correo = trim($_POST['correo']);
$password = $_POST['password'];

$ip = $_SERVER['REMOTE_ADDR'];

$usuario = new Usuario($pdo);

$datos = $usuario->obtenerPorCorreo($correo);

if(!$datos){

    $usuario->registrarAuditoria($correo, "fail", $ip);

    mostrarError("Usuario no encontrado");
}

if(!password_verify($password, $datos['password'])){

    $usuario->registrarAuditoria($correo, "fail", $ip);

    mostrarError("Contraseña incorrecta");
}

$_SESSION['usuario_id'] = $datos['id'];
$_SESSION['correo'] = $datos['correo'];
$_SESSION['pendiente_2fa'] = true;

header("Location: ../Views/Codigo_2fa.php");
exit;



function mostrarError($mensaje){
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Error de Login</title>

        <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI', sans-serif;
            background:#F5F7FB;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
        }

        .card{
            background:white;
            width:100%;
            max-width:450px;
            padding:40px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            text-align:center;
        }

        .logo{
            font-size:60px;
            margin-bottom:15px;
        }

        h2{
            color:#344054;
            margin-bottom:10px;
        }

        .error{
            background:#FEE4E2;
            color:#B42318;
            padding:12px;
            border-radius:12px;
            margin:20px 0;
            font-weight:600;
            border:1px solid #FDA29B;
        }

        a{
            display:inline-block;
            margin-top:10px;
            padding:14px 18px;
            background:#A7C7FF;
            color:#1D2939;
            text-decoration:none;
            border-radius:12px;
            font-weight:600;
            transition:.3s;
        }

        a:hover{
            transform:translateY(-2px);
            background:#92B8FB;
        }

        .footer{
            margin-top:20px;
            font-size:12px;
            color:#98A2B3;
        }

        </style>

    </head>
    <body>

        <div class='card'>

            <div class='logo'>⚠️</div>

            <h2>Error de autenticación</h2>

            <div class='error'>
                $mensaje
            </div>

            <a href='../Views/Login_form.php'>Volver al login</a>

            <div class='footer'>
                Sistema de autenticación segura con 2FA
            </div>

        </div>

    </body>
    </html>
    ";
    exit;
}