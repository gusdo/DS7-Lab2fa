<?php

session_start();

$_SESSION['token'] =
    bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Iniciar Sesión</h2>

<form action="../Auth/login.php" method="POST">

    <input
        type="hidden"
        name="token"
        value="<?= $_SESSION['token']; ?>">

    <input
        type="email"
        name="correo"
        placeholder="Correo"
        required>

    <br><br>

    <input
        type="password"
        name="password"
        placeholder="Contraseña"
        required>

    <br><br>

    <button type="submit">
        Iniciar Sesión
    </button>

</form>

</body>
</html>