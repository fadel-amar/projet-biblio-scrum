<?php

namespace App\UserStories\creerLivre;

use Symfony\Component\Validator\Constraints as Assert;

class creerLivreRequete
{

    #[Assert\NotBlank( message: "Isbn est obligatoire")]
    public string $isbn;

    #[Assert\NotBlank(message: "L'auteur est obligatoire")]
    public string $auteur;

    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\GreaterThan( 0 , message:"Le nombre de pages soit être supérieur à 0") ]
    public string $nbPages;

    /**
     * @param string $isbn
     * @param string $auteur
     * @param string $nbPages
     */
    public function __construct(string $isbn, string $auteur, string $nbPages)
    {
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->nbPages = $nbPages;
    }



}