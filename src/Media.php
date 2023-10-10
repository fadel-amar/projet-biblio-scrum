<?php
namespace App;

abstract class Media
{
    protected int $dureeEmprunt;
    protected string $titre;
    protected string $status;
    protected \DateTime $dateCreation;

    public function __construct()
    {

    }
}
