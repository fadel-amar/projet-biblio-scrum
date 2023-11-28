<?php

namespace App\Services;

class GenerateurNumeroAdherent
{
    public function generer(): string
    {
        $numero = rand(0, 999999);
        while (strlen($numero) < 6) {
            $numero = '0'.$numero;
        }
        return "AD-$numero";
    }
}
