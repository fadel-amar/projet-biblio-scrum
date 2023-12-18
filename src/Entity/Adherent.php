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
class
Adherent
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id;

    #[Column(name : 'numero_adherent', length: 9)]
    private string $numero_adherent;
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
    public function AdhesionValide() :bool{

        $interval = (new \DateInterval("P1Y"));
        $dateValidite = \DateTimeImmutable::createFromMutable($this->dateAdhesion)->add($interval);

        if (new \DateTimeImmutable() > $dateValidite ) {
            return false;
        }
        return  true;
    }


    public function setNumeroAdherent(string $numero_adherent): void
    {
        $this->numero_adherent = $numero_adherent;
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
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getNumeroAdherent(): string
    {
        return $this->numero_adherent;
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
