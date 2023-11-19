<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


// todo remplacer adherent en adherent
#[Entity]
#[Table(name: 'Adherent')]
class Adherent
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id_adherent;

    #[Column(name : 'numero_adherent', length: 9)]
    private string $numeroAdherent;
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
     * @param string $numeroAdherent
     */
    public function setNumeroAdherent(string $numeroAdherent): void
    {
        $this->numeroAdherent = $numeroAdherent;
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

    /**
     * @return int
     */
    public function getIdAdherent(): int
    {
        return $this->id_adherent;
    }

    /**
     * @return string
     */
    public function getNumeroAdherent(): string
    {
        return $this->numeroAdherent;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdhesion(): \DateTime
    {
        return $this->dateAdhesion;
    }




}
