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

}