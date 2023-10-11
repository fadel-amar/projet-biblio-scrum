<?php

namespace App;

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

    public function empruntEnRetard() {
        if (!isset($this->dateRetour) and  (new \DateTime())> $this->dateRetourEstime){
            return true;
        }
        return false;
    }



}