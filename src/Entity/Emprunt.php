<?php

namespace App\Entity;

use DateTime;

class Emprunt
{
    private DateTime $dateEmprunt;
    private DateTime $dateRetourEstime;
    private ?DateTime $dateRetour;
    private Adherant $adherant;
    private Media $media;


    public function __construct()
    {
    }

    public function empruntEnCours(): bool {
        if (!isset($this->dateRetour)) {
            return true;
        } else {
            return false;
        }
    }

    public function empruntEnRetard() : bool {
        if (!isset($this->dateRetour) and  (new \DateTime())> $this->dateRetourEstime){
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

    /**
     * @param DateTime $dateRetourEstime
     */
    public function setDateRetourEstime(DateTime $dateRetourEstime): void
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
     * @return Adherant
     */
    public function getAdherant(): Adherant
    {
        return $this->adherant;
    }

    /**
     * @param Adherant $adherant
     */
    public function setAdherant(Adherant $adherant): void
    {
        $this->adherant = $adherant;
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

}