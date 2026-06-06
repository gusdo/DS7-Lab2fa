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
        }

        .logo{
            text-align:center;
            font-size:60px;
            margin-bottom:15px;
        }

        h2{
            text-align:center;
            color:#344054;
            margin-bottom:10px;
        }

        .subtitulo{
            text-align:center;
            color:#667085;
            margin-bottom:30px;
        }

        .grupo{
            margin-bottom:18px;
        }

        label{
            display:block;
            margin-bottom:8px;
            color:#344054;
            font-weight:500;
        }

        input{
            width:100%;
            padding:14px;
            border:1px solid #E4E7EC;
            border-radius:12px;
            font-size:15px;
            outline:none;
            transition:.3s;
        }

        input:focus{
            border-color:#A7C7FF;
            box-shadow:0 0 0 4px rgba(167,199,255,.20);
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            border-radius:12px;
            background:#A7C7FF;
            color:#1D2939;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        button:hover{
            transform:translateY(-2px);
            background:#92B8FB;
        }

        .footer{
            text-align:center;
            margin-top:20px;
            color:#98A2B3;
            font-size:13px;
        }

        .registro-btn{
    display:block;
    text-align:center;
    margin-top:15px;
    padding:14px;
    border-radius:12px;
    background:#E4E7EC;
    color:#344054;
    font-weight:600;
    text-decoration:none;
    transition:.3s;
}

.registro-btn:hover{
    background:#D0D5DD;
    transform:translateY(-2px);
}

    </style>

</head>
<body>

<div class="card">

    <div class="logo">
        🔐
    </div>

    <h2>Iniciar Sesión</h2>

    <?php if(isset($_SESSION['error'])): ?>

    <div class="alerta">

        <?= $_SESSION['error']; ?>

    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>

    <p class="subtitulo">
        Acceda al sistema
    </p>

    <form action="../Auth/login.php" method="POST">

        <input
            type="hidden"
            name="token"
            value="<?= $_SESSION['token']; ?>">

        <div class="grupo">

            <label>Correo Electrónico</label>

            <input
                type="email"
                name="correo"
                placeholder="correo@ejemplo.com"
                required>

        </div>

        <div class="grupo">

            <label>Contraseña</label>

            <input
                type="password"
                name="password"
                placeholder="Ingrese su contraseña"
                required>

        </div>

        <button type="submit">
            Iniciar Sesión
        </button>

        <a href="registro_form.php" class="registro-btn">
    Crear cuenta
</a>

    </form>

    <div class="footer">
        Sistema protegido con autenticación de dos factores (2FA)
    </div>

</div>

</body>
</html>