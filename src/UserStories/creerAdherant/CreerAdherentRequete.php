<?php
namespace  App\UserStories;
use Symfony\Component\Validator\Constraints as Assert;


class CreerAdherentRequete {

    #[Assert\NotBlank( message: "Le prenom est obligatoire")]
    public string $prenom;

    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    public string $nom;

    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email {{ value}} doit est invalide :( ")]
    public string $email;


    /**
     * @param string $prenom
     * @param string $nom
     * @param string $email
     */
    public function __construct(string $prenom, string $nom, string $email)
    {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
    }




}