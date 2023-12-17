<?php


namespace App\UserStories\creerMagazine;

use Symfony\Component\Validator\Constraints as Assert;

class CreerMagazineRequete
{


    #[Assert\NotBlank(message: "Le numero de magazine est obligatoire")]
    public ?string $numero;

    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    public ?string $titre;

    #[Assert\NotBlank(message: "La date de publication est obligatoire")]
    public ?string $datePublication;


    public function __construct(?string $numero, ?string $titre, ?string  $datePublication)
    {

        $this->titre = $titre;
        $this->numero = $numero;
        $this->datePublication = $datePublication;
    }






}