<?php

namespace App\UserStories\emprunterMedia;
use Symfony\Component\Validator\Constraints as Assert;

class EmprunterMediaRequete {

    #[Assert\NotBlank( message: "L'id du media est obligatoire")]
    public ?int $idMedia;

    #[Assert\NotBlank(message: "Le numero adherent est obligatoire")]
    public ?string $numeroAdherent;

    public function __construct(?int $idMedia, ?string $numeroAdherent)
    {
        $this->idMedia = $idMedia;
        $this->numeroAdherent = $numeroAdherent;
    }

}