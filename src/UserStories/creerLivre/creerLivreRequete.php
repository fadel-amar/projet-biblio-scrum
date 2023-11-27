<?php

namespace App\UserStories\creerLivre;

use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete
{

    #[Assert\NotBlank( message: "L'isbn est obligatoire")]
    public string $isbn;


    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    public string $titre;

    #[Assert\NotBlank(message: "L'auteur est obligatoire")]
    public string $auteur;

    #[Assert\GreaterThan( 0 , message:"Le nombre de pages doit être supérieur à 0") ]
    #[Assert\NotBlank(message: "Le nombre de pages est obligatoire")]
    public ?int $nbPages;


    #[Assert\NotBlank(message: "La date de parution est obligatoire")]
    public string $dateCreation;


    public function __construct(string $isbn, string $auteur,string $titre, string $dateCreation, ?int $nbPages=null)
    {
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->titre = $titre;
        $this->nbPages = $nbPages;
        $this->dateCreation = $dateCreation;
    }



}