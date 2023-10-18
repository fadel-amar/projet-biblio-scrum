<?php

namespace App\Entity;

class Livre extends Media
{
    private string $isbn;
    private string $auteur;
    private int $nbPages;

    public function __construct()
    {
    }
}