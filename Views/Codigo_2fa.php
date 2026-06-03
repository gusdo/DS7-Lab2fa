<?php

session_start();

if(!isset($_SESSION['pendiente_2fa'])){
    die("Acceso no autorizado");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verificación 2FA</title>
</head>
<body>

<h2>Ingrese el Código de Google Authenticator</h2>

<form action="../Auth/Verificar_2fa.php" method="POST">

    <input
        type="text"
        name="codigo"
        placeholder="Código de 6 dígitos"
        required>

    <br><br>

    <button type="submit">
        Verificar
    </button>

</form>

</body>
</html>