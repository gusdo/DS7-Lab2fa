<?php

session_start();

require "../Config/db.php";
require "../Clases/Usuario.php";
require "../Clases/Sanitizar.php";
require "../vendor/autoload.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

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
| Sanitizar datos
|--------------------------------------------------------------------------
*/

$nombre = Sanitizar::texto($_POST['nombre']);
$apellido = Sanitizar::texto($_POST['apellido']);
$correo = Sanitizar::correo($_POST['correo']);
$sexo = Sanitizar::sexo($_POST['sexo']);

$password = $_POST['password'];

$usuario = new Usuario($pdo);

/*
|--------------------------------------------------------------------------
| Verificar correo duplicado
|--------------------------------------------------------------------------
*/

if($usuario->existeCorreo($correo)){
    die("El correo ya existe");
}

/*
|--------------------------------------------------------------------------
| Crear hash de contraseña
|--------------------------------------------------------------------------
*/

$hash = $usuario->generarHash($password);

/*
|--------------------------------------------------------------------------
| Guardar usuario
|--------------------------------------------------------------------------
*/

$usuario->guardarUsuario(
    $nombre,
    $apellido,
    $correo,
    $hash,
    $sexo
);

/*
|--------------------------------------------------------------------------
| Generar secreto 2FA
|--------------------------------------------------------------------------
*/

$g = new GoogleAuthenticator();

$secret = $g->generateSecret();

/*
|--------------------------------------------------------------------------
| Guardar secreto
|--------------------------------------------------------------------------
*/

$usuario->guardarSecreto2FA(
    $correo,
    $secret
);

/*
|--------------------------------------------------------------------------
| Generar URL OTP
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| Generar QR
|--------------------------------------------------------------------------
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
</head>
<body>

    <h2>Registro Exitoso</h2>

    <p>
        Escanea este código con Google Authenticator
    </p>

    <img src="<?= $qr_url ?>" alt="QR 2FA">

    <br><br>

    <strong>Secreto:</strong>

    <?= $secret ?>

    <br><br>

    <small>
        Si el QR falla, puedes agregar la cuenta manualmente usando el secreto mostrado arriba.
    </small>

    <br><br>

    <a href="../Views/Login_Form.php">
        Ir al Login
    </a>

</body>
</html>