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
            background:#FFFFFF;
            width:100%;
            max-width:450px;
            padding:40px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.08);
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
            margin-bottom:30px;
            line-height:1.5;
        }

        input{
            width:100%;
            padding:14px;
            border:1px solid #E4E7EC;
            border-radius:12px;
            font-size:16px;
            text-align:center;
            letter-spacing:3px;
            margin-bottom:20px;
            outline:none;
            transition:.3s;
        }

        input:focus{
            border-color:#7DA8F7;
            box-shadow:0 0 0 4px rgba(125,168,247,.15);
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            border-radius:12px;
            background:#7DA8F7;
            color:white;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        button:hover{
            background:#6A98F0;
        }

        .info{
            margin-top:20px;
            font-size:13px;
            color:#98A2B3;
        }

    </style>

</head>
<body>

<div class="card">

    <div class="icono">
        🔐
    </div>

    <h2>Verificación de Seguridad</h2>

    <p class="descripcion">
        Ingrese el código de 6 dígitos generado por Google Authenticator para continuar.
    </p>

    <form action="../Auth/Verificar_2fa.php" method="POST">

        <input
            type="text"
            name="codigo"
            placeholder="000000"
            maxlength="6"
            required>

        <button type="submit">
            Verificar Código
        </button>

    </form>

    <div class="info">
        Autenticación de dos factores (2FA)
    </div>

</div>

</body>
</html>