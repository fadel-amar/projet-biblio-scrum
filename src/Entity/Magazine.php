<?php

namespace App\Entity;

class Magazine extends Media
{
    private int $numero;
    private \DateTime $datePublication;

    public function __construct()
    {
    }
}