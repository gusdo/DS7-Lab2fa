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

        body{
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .contenedor{
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }

        h1{
            color: #2c3e50;
        }

        .correo{
            font-size: 18px;
            margin-bottom: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th{
            background-color: #3498db;
            color: white;
            padding: 10px;
        }

        table td{
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .success{
            color: green;
            font-weight: bold;
        }

        .fail{
            color: red;
            font-weight: bold;
        }

        .logout{
            display: inline-block;
            margin-top: 20px;
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout:hover{
            background-color: #c0392b;
        }

    </style>

</head>
<body>

<div class="contenedor">

    <h1>Bienvenido al Sistema</h1>

    <p class="correo">
        <strong>Correo:</strong>
        <?= $_SESSION['correo']; ?>
    </p>

    <h2>Historial de Accesos</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Correo</th>
            <th>Estado</th>
            <th>Fecha</th>
        </tr>

        <?php foreach($auditorias as $fila): ?>

        <tr>

            <td><?= $fila['id']; ?></td>

            <td><?= $fila['correo']; ?></td>

            <td class="<?= $fila['estado']; ?>">

                <?= strtoupper($fila['estado']); ?>

            </td>

            <td><?= $fila['fecha']; ?></td>

        </tr>

        <?php endforeach; ?>

    </table>

    <a
        class="logout"
        href="../Auth/Logout.php">

        Cerrar Sesión

    </a>

</div>

</body>
</html>