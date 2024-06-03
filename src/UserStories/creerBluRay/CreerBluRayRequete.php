<?php

namespace App\UserStories\creerBluRay;

use Symfony\Component\Validator\Constraints as Assert;

class CreerBluRayRequete
{
    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    #[Assert\Type(type: 'string', message:"Le titre doit être une chaîne de caractères")]
    public ?string $titre;

    #[Assert\NotBlank(message: "Le nom du réalisateur est obligatoire")]
    #[Assert\Type(type: 'string', message: "Le nom du réalisateur doit être une chaîne de caractères")]
    public ?string $realisateur;

    #[Assert\NotBlank(message: "L'année de publication est obligatoire")]
    #[Assert\Type(type:'integer',message: "L'année de publication doit être un nombre entier")]
    public ?int $anneePublication;

    #[Assert\NotBlank(message: "La durée est obligatoire")]
    #[Assert\Type(type:'integer',message: "La durée doit être un nombre entier et être en minutes")]
    public ?int $duree;

    /**
     * @param string|null $titre
     * @param string|null $realisateur
     */
    public function __construct(?string $titre, ?string $realisateur,  ?int $anneePublication,  ?int $duree)
    {
        $this->titre = $titre;
        $this->realisateur = $realisateur;
        $this->anneePublication = $anneePublication;
        $this->duree = $duree;
    }


}