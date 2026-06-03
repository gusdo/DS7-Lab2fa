<?php

session_start();

$_SESSION['token'] =
    bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h2>Registro de Usuario</h2>

<form action="../Auth/Registrar.php" method="POST">

    <input
        type="hidden"
        name="token"
        value="<?= $_SESSION['token']; ?>">

    <input
        type="text"
        name="nombre"
        placeholder="Nombre"
        required>

    <br><br>

    <input
        type="text"
        name="apellido"
        placeholder="Apellido"
        required>

    <br><br>

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

    <select name="sexo">

        <option value="M">
            Masculino
        </option>

        <option value="F">
            Femenino
        </option>

    </select>

    <br><br>

    <button type="submit">
        Registrarse
    </button>

</form>

</body>
</html>