<?php

namespace App\UserStories\creerLivre;

use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete
{

    #[Assert\NotBlank( message: "L'isbn est obligatoire")]
    #[Assert\Isbn(message: "L'Isbn n'est pas valide")]
    public ?string $isbn;


    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    public ?string $titre;

    #[Assert\NotBlank(message: "L'auteur est obligatoire")]
    public ?string $auteur;

    #[Assert\GreaterThan( 0 , message:"Le nombre de pages doit être supérieur à 0") ]
    #[Assert\NotBlank(message: "Le nombre de pages est obligatoire")]
    public ?int $nbPages;



    public function __construct(?string $isbn, ?string $auteur, ?string $titre, ?int $nbPages=null)
    {
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->titre = $titre;
        $this->nbPages = $nbPages;
    }






}