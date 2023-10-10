<?php

namespace App;

class Livre extends Media
{
    private string $isbn;
    private string $auteur;
    private int $nbPages;

    public function __construct()
    {
    }
}