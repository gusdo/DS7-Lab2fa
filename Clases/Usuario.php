<?php

class Usuario{

    private $pdo;

    public function __construct($pdo){

        $this->pdo = $pdo;
    }

    public function existeCorreo($correo){

        $sql = "SELECT id
                FROM usuarios
                WHERE correo = ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$correo]);

        return $stmt->rowCount() > 0;
    }

    public function generarHash($password){

        return password_hash(
            $password,
            PASSWORD_DEFAULT
        );
    }

    public function guardarUsuario(
        $nombre,
        $apellido,
        $correo,
        $password,
        $sexo
    ){

        $sql = "INSERT INTO usuarios
                (
                    nombre,
                    apellido,
                    correo,
                    password,
                    sexo
                )
                VALUES
                (
                    ?,?,?,?,?
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $nombre,
            $apellido,
            $correo,
            $password,
            $sexo
        ]);
    }

    public function guardarSecreto2FA(
        $correo,
        $secret
    ){

        $sql = "UPDATE usuarios
                SET secret_2fa = ?
                WHERE correo = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $secret,
            $correo
        ]);
    }

    public function obtenerPorCorreo($correo)
    {

        $sql = "SELECT *
                FROM usuarios
                WHERE correo = ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$correo]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarAuditoria(
        $correo,
        $estado,
        $ip
    ){

        $sql = "INSERT INTO login_audit
                (
                    correo,
                    estado,
                    ip
                )
                VALUES
                (
                    ?,?,?
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $correo,
            $estado,
            $ip
        ]);
    }

}