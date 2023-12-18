<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Emprunt
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 12, unique: true)]
    private $numeroEmprunt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $dateEmprunt;
    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $dateRetourEstime;
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $dateRetour;

    #[ORM\ManyToOne(targetEntity: "Media")]
    #[ORM\JoinColumn(name: "id_media", referencedColumnName: "id")]
    private Media $media;
    #[ORM\ManyToOne(targetEntity: "Adherent")]
    #[ORM\JoinColumn(name: "id_adherent", referencedColumnName: "id")]
    private Adherent $adherent;


    public function __construct()
    {
    }

    public function calculDateRetourEstime(\DateTime $dateEmprunt, int $dureeEmprunt)
    {
        $interval = new \DateInterval("P{$dureeEmprunt}D");
        $dateRetourEstime = \DateTimeImmutable::createFromMutable($dateEmprunt)->add($interval);
        return $dateRetourEstime;
    }


    public function empruntEnCours(): bool
    {
        if (!isset($this->dateRetour)) {
            return true;
        } else {
            return false;
        }
    }

    public function empruntEnRetard(): bool
    {
        if ($this->empruntEnCours() && (new \DateTime()) > $this->dateRetourEstime) {
            return true;
        }
        return false;
    }

    /**
     * @return DateTime
     */
    public function getDateEmprunt(): DateTime
    {
        return $this->dateEmprunt;
    }

    /**
     * @param DateTime $dateEmprunt
     */
    public function setDateEmprunt(DateTime $dateEmprunt): void
    {
        $this->dateEmprunt = $dateEmprunt;
    }

    /**
     * @return DateTime
     */
    public function getDateRetourEstime(): DateTime
    {
        return $this->dateRetourEstime;
    }


    public function setDateRetourEstime(\DateTimeImmutable $dateRetourEstime): void
    {
        $this->dateRetourEstime = $dateRetourEstime;
    }

    /**
     * @return DateTime|null
     */
    public function getDateRetour(): ?DateTime
    {
        return $this->dateRetour;
    }

    /**
     * @param DateTime|null $dateRetour
     */
    public function setDateRetour(?DateTime $dateRetour): void
    {
        $this->dateRetour = $dateRetour;
    }

    /**
     * @return Adherent
     */
    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    /**
     * @param Adherent $adherent
     */
    public function setAdherent(Adherent $adherent): void
    {
        $this->adherent = $adherent;
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia(Media $media): void
    {
        $this->media = $media;
    }

    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getNumeroEmprunt()
    {
        return $this->numeroEmprunt;
    }

    /**
     * @param mixed $numeroEmprunt
     */
    public function setNumeroEmprunt($numeroEmprunt): void
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }


}