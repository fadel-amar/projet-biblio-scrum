<?php

namespace App\UserStories\retourEmprunt;
use Symfony\Component\Validator\Constraints as Assert;

class RetourEmpruntRequete
{

    #[Assert\NotBlank( message: "Le numéro d'emprunt est obligatoire")]
    public ?string $numeroEmprunt;

    public function __construct(?string $numeroEmprunt)
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }


}