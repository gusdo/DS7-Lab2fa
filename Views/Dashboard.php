<?php

session_start();

if(!isset($_SESSION['autenticado'])){
    header("Location: Login_Form.php");
    exit;
}

require "../Config/db.php";

$sql = "SELECT *
        FROM login_audit
        WHERE correo = ?
        ORDER BY fecha DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $_SESSION['correo']
]);

$auditorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI', sans-serif;
            background:#F5F7FB;
            color:#344054;
            padding:30px;
        }

        .contenedor{
            max-width:1200px;
            margin:auto;
        }

        .header{
            background:white;
            padding:25px 30px;
            border-radius:20px;
            box-shadow:0 8px 25px rgba(0,0,0,.06);
            margin-bottom:25px;
        }

        .titulo{
            font-size:30px;
            font-weight:600;
            margin-bottom:8px;
        }

        .correo{
            color:#667085;
            font-size:15px;
        }

        .acciones{
            margin-top:20px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }

        .btn{
            text-decoration:none;
            padding:12px 20px;
            border-radius:12px;
            font-weight:600;
            transition:.3s;
        }


        .btn-logout{
            background:#F7B4B4;
            color:#7A2020;
        }

        .btn-logout:hover{
            transform:translateY(-2px);
        }

        .card{
            background:white;
            border-radius:20px;
            padding:25px;
            box-shadow:0 8px 25px rgba(0,0,0,.06);
        }

        .card h2{
            margin-bottom:20px;
            color:#344054;
        }

        table{
            width:100%;
            border-collapse:collapse;
            overflow:hidden;
            border-radius:15px;
        }

        thead{
            background:#A7C7FF;
        }

        th{
            padding:15px;
            text-align:center;
            color:#1D2939;
            font-weight:600;
        }

        td{
            padding:14px;
            text-align:center;
            border-bottom:1px solid #EEF2F6;
        }


        .success{
            color:#12B76A;
            font-weight:bold;
        }

        .fail{
            color:#F04438;
            font-weight:bold;
        }

        .estado-badge{
            padding:6px 12px;
            border-radius:20px;
            display:inline-block;
        }

        .success.estado-badge{
            background:#D1FADF;
        }

        .fail.estado-badge{
            background:#FEE4E2;
        }

        .estadisticas{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
            margin-bottom:25px;
        }

        .stat{
            background:white;
            padding:20px;
            border-radius:20px;
            box-shadow:0 8px 25px rgba(0,0,0,.06);
        }

        .stat h3{
            font-size:14px;
            color:#667085;
            margin-bottom:10px;
        }

        .stat .numero{
            font-size:28px;
            font-weight:700;
            color:#344054;
        }

    </style>

</head>
<body>

<div class="contenedor">

    <div class="header">

        <div class="titulo">
            Usuario conectado:
            <strong><?= $_SESSION['correo']; ?></strong>
        </div>

        

        <div class="acciones">

            <a
                href="../Auth/Logout.php"
                class="btn btn-logout">

                🚪 Cerrar Sesión

            </a>

        </div>

    </div>

    <div class="estadisticas">

        <div class="stat">
            <h3>Total de accesos</h3>
            <div class="numero">
                <?= count($auditorias); ?>
            </div>
        </div>

        <div class="stat">
            <h3>Última actividad</h3>
            <div class="numero" style="font-size:16px;">
                <?= count($auditorias) ? $auditorias[0]['fecha'] : 'Sin registros'; ?>
            </div>
        </div>

    </div>

    <div class="card">

        <h2>Historial de Accesos</h2>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>IP</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($auditorias as $fila): ?>

            <tr>

                <td><?= $fila['id']; ?></td>

                <td><?= $fila['correo']; ?></td>

                <td>
                    <span class="<?= $fila['estado']; ?> estado-badge">
                        <?= strtoupper($fila['estado']); ?>
                    </span>
                </td>

                <td><?= $fila['ip']; ?></td>

                <td><?= $fila['fecha']; ?></td>

            </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>