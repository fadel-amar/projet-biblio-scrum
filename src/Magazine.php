<?php

namespace App;

class Magazine extends Media
{
    private int $numero;
    private \DateTime $datePublication;

    public function __construct()
    {
    }
}