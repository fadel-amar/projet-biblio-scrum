<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class Magazine extends Media
{

    #[ORM\Column(type: 'integer')]
    private int $numero;


    #[ORM\Column(type: 'date')]
    private \DateTime $datePublication;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return \DateTime
     */
    public function getDatePublication(): \DateTime
    {
        return $this->datePublication;
    }


    public function setDatePublication(string $datePublication): void
    {
        $this->datePublication = \DateTime::createFromFormat('d/m/Y', $datePublication) ;
    }


}