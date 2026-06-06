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
            max-width:500px;
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

        input,
        select{
            width:100%;
            padding:14px;
            border:1px solid #E4E7EC;
            border-radius:12px;
            font-size:15px;
            outline:none;
            transition:.3s;
            background:white;
        }

        input:focus,
        select:focus{
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
            background:#92B8FB;
            transform:translateY(-2px);
        }

        #mensajeCorreo{
            display:block;
            margin-top:8px;
            font-size:14px;
            font-weight:600;
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
        👤
    </div>

    <h2>Crear Cuenta</h2>

    <p class="subtitulo">
        Registre un nuevo usuario en el sistema
    </p>

    <form action="../Auth/Registrar.php" method="POST">

        <input
            type="hidden"
            name="token"
            value="<?= $_SESSION['token']; ?>">

        <div class="grupo">

            <label>Nombre</label>

            <input
                type="text"
                name="nombre"
                placeholder="Ingrese su nombre"
                required>

        </div>

        <div class="grupo">

            <label>Apellido</label>

            <input
                type="text"
                name="apellido"
                placeholder="Ingrese su apellido"
                required>

        </div>

        <div class="grupo">

            <label>Correo Electrónico</label>

            <input
                type="email"
                id="correo"
                name="correo"
                placeholder="correo@ejemplo.com"
                required>

            <span id="mensajeCorreo"></span>

        </div>

        <div class="grupo">

            <label>Contraseña</label>

            <input
                type="password"
                name="password"
                placeholder="Ingrese una contraseña"
                required>

        </div>

        <div class="grupo">

            <label>Sexo</label>

            <select name="sexo">

                <option value="M">
                    Masculino
                </option>

                <option value="F">
                    Femenino
                </option>

            </select>

        </div>

        <button type="submit">
            Registrarse
        </button>

    </form>

    <div class="footer">
        El sistema solicitará configuración de Google Authenticator al finalizar el registro.
    </div>

    <a href="login_form.php" class="registro-btn">
    Iniciar sesión
</a>

</div>

<script>

document
.getElementById("correo")
.addEventListener("blur", function(){

    let correo = this.value;

    if(correo === ""){
        return;
    }

    fetch(
        "../Auth/VerificarCorreo.php?correo="
        + encodeURIComponent(correo)
    )
    .then(response => response.text())
    .then(data => {

        document
        .getElementById("mensajeCorreo")
        .innerHTML = data;

    });

});

</script>

</body>
</html>