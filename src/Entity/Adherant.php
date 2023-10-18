<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity]
#[Table(name: 'Adherant')]
class Adherant
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id_adherant;

    #[Column(name : 'numero_adherant', length: 9)]
    private string $numeroAdherant;
    #[Column(name : 'prenom',length: 100)]
    private string $prenom;
    #[Column(name : 'nom',length: 100)]
    private string $nom;
    #[Column(name : 'email',length: 160)]
    private string $email;
    #[Column(name: 'date_adhesion', type:'date')]
    private \DateTime $dateAdhesion;


    public function __construct()
    {

    }

    /**
     * @param string $numeroAdherant
     */
    public function setNumeroAdherant(string $numeroAdherant): void
    {
        $this->numeroAdherant = $numeroAdherant;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param \DateTime $dateAdhesion
     */
    public function setDateAdhesion(\DateTime $dateAdhesion): void
    {
        $this->dateAdhesion = $dateAdhesion;
    }



}
