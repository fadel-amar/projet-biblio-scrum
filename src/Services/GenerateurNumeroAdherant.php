<?php

namespace App\Services;

class GenerateurNumeroAdherant
{
    // todo faire un test pour generatuer adherant
    public function generer(): string
    {
        $numero = rand(0, 999999);
        while (strlen($numero) < 6) {
            $numero = '0'.$numero;
        }
        return "AD-$numero";
    }
}
