<?php
namespace App;

abstract class Media
{
    protected int $id;
    protected int $dureeEmprunt;
    protected string $titre;
    protected string $status;
    protected \DateTime $dateCreation;

    public function __construct()
    {

    }
}
