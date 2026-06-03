<?php

class Sanitizar{

    public static function texto($texto){

        return htmlspecialchars(
            trim(strip_tags($texto))
        );
    }

    public static function correo($correo){

        return filter_var(
            $correo,
            FILTER_SANITIZE_EMAIL
        );
    }

    public static function sexo($sexo){

        return in_array($sexo,['M','F'])
            ? $sexo
            : null;
    }

}