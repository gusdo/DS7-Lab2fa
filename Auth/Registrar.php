<?php

session_start();

require "../Config/db.php";
require "../Clases/Usuario.php";
require "../Clases/Sanitizar.php";
require "../vendor/autoload.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

/*
Validar Token CSRF
*/

if(
    !isset($_POST['token']) ||
    !isset($_SESSION['token']) ||
    $_POST['token'] !== $_SESSION['token']
){
    die("Token CSRF inválido");
}

/*
Sanitizar datos
*/

$nombre = Sanitizar::texto($_POST['nombre']);
$apellido = Sanitizar::texto($_POST['apellido']);
$correo = Sanitizar::correo($_POST['correo']);
$sexo = Sanitizar::sexo($_POST['sexo']);

$password = $_POST['password'];

$usuario = new Usuario($pdo);

/*
Verificar correo duplicado
*/

if($usuario->existeCorreo($correo)){
    die("El correo ya existe");
}

/*
Crear hash de contraseña
*/

$hash = $usuario->generarHash($password);

/*
Guardar usuario
*/

$usuario->guardarUsuario(
    $nombre,
    $apellido,
    $correo,
    $hash,
    $sexo
);

/*
Generar secreto 2FA
*/

$g = new GoogleAuthenticator();

$secret = $g->generateSecret();

/*
Guardar secreto
*/

$usuario->guardarSecreto2FA(
    $correo,
    $secret
);

/*
Generar URL OTP
*/

$app = "Laboratorio2FA";

$otpauth =
    "otpauth://totp/" .
    urlencode($app) .
    ":" .
    urlencode($correo) .
    "?secret=" .
    $secret .
    "&issuer=" .
    urlencode($app);

/*
Generar QR
*/

$qr_url =
    "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data="
    . urlencode($otpauth);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>

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
            padding:20px;
        }

        .card{
            background:white;
            width:100%;
            max-width:600px;
            padding:40px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            text-align:center;
        }

        .icono{
            font-size:60px;
            margin-bottom:15px;
        }

        h2{
            color:#344054;
            margin-bottom:10px;
        }

        .descripcion{
            color:#667085;
            margin-bottom:25px;
            line-height:1.6;
        }

        .qr{
            margin:25px 0;
        }

        .qr img{
            border-radius:15px;
            padding:10px;
            background:white;
            border:1px solid #E4E7EC;
        }

        .secreto-box{
            background:#F8F9FC;
            border:1px solid #E4E7EC;
            border-radius:12px;
            padding:15px;
            margin:20px 0;
        }

        .secreto-titulo{
            color:#344054;
            font-weight:600;
            margin-bottom:10px;
        }

        .secreto{
            font-size:18px;
            font-weight:bold;
            letter-spacing:2px;
            color:#7DA8F7;
            word-break:break-all;
        }

        .nota{
            background:#FFF8E7;
            color:#8A6D3B;
            padding:15px;
            border-radius:12px;
            margin-top:20px;
            line-height:1.5;
        }

        .btn{
            display:inline-block;
            margin-top:25px;
            padding:14px 25px;
            background:#A7C7FF;
            color:#1D2939;
            text-decoration:none;
            border-radius:12px;
            font-weight:600;
            transition:.3s;
        }

        .btn:hover{
            background:#92B8FB;
            transform:translateY(-2px);
        }

    </style>

</head>
<body>

<div class="card">

    <div class="icono">
        ✅
    </div>

    <h2>Registro Exitoso</h2>

    <p class="descripcion">
        Su cuenta ha sido creada correctamente.
        Para activar la autenticación de dos factores (2FA),
        escanee el siguiente código QR con Google Authenticator.
    </p>

    <div class="qr">
        <img src="<?= $qr_url ?>" alt="QR 2FA">
    </div>

    <div class="secreto-box">

        <div class="secreto-titulo">
            Clave Secreta
        </div>

        <div class="secreto">
            <?= $secret ?>
        </div>

    </div>

    <div class="nota">
        Si no puede escanear el código QR, agregue la cuenta manualmente
        en Google Authenticator utilizando la clave secreta mostrada arriba.
    </div>

    <a
        href="../Views/Login_Form.php"
        class="btn">

        Ir al Login

    </a>

</div>

</body>
</html>