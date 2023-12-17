<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["livre" => "Livre", "magazine"=>"Magazine"])]
abstract class Media {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'integer')]
    protected ?int $dureeEmprunt;

    #[ORM\Column(type: 'string', length: 150)]
    protected ?string $titre;

    #[ORM\Column(type: 'string', length: 100)]
    protected ?string $status;

    #[ORM\Column(length: 50)]
    protected  ?\DateTime $dateCreation;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getDureeEmprunt(): int
    {
        return $this->dureeEmprunt;
    }

    /**
     * @param int $dureeEmprunt
     */
    public function setDureeEmprunt(int $dureeEmprunt): void
    {
        $this->dureeEmprunt = $dureeEmprunt;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }


    public function setDateCreation(\DateTime $dateCreation): void
    {
        $this->dateCreation =  $dateCreation;
    }


}
