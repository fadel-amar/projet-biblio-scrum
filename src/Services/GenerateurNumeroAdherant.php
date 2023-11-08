<?php

namespace App\Services;

class GenerateurNumeroAdherant
{
    public function generer(): string
    {
        $numero = random_int(100000,999999);
        return "AD-$numero";
    }
}
