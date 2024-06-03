<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class BluRay extends Media {

    #[ORM\Column(type: 'string', length: 100 )]
    private string $realisateur;

    #[ORM\Column(type: 'integer')]
    private int  $duree;

    #[ORM\Column(type: 'integer')]
    private int $anneeSortie;



    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getRealisateur(): string
    {
        return $this->realisateur;
    }

    /**
     * @param string $realisateur
     */
    public function setRealisateur(string $realisateur): void
    {
        $this->realisateur = $realisateur;
    }

    /**
     * @return int
     */
    public function getDuree(): int
    {
        return $this->duree;
    }

    /**
     * @param int $duree
     */
    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return int
     */
    public function getAnneeSortie(): int
    {
        return $this->anneeSortie;
    }

    /**
     * @param int $anneeSortie
     */
    public function setAnneeSortie(int $anneeSortie): void
    {
        $this->anneeSortie = $anneeSortie;
    }



}