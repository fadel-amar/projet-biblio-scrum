<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class Magazine extends Media
{


    #[ORM\Column(type: 'string')]
    private int $numero;

    #[ORM\Column(type: 'string')]
    private string $datePublication;

    public function __construct()
    {
    }


    public function getNumero(): string
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(string $numero): void
    {
        $this->numero = $numero;
    }


    public function getDatePublication(): string
    {
        return $this->datePublication;
    }


    public function setDatePublication(string $datePublication): void
    {
        $this->datePublication = $datePublication;
    }


}