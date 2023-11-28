<?php
require "./bootstrap.php";

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ValidatorBuilder;


$validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
$requete = new \App\UserStories\creerMagazine\CreerMagazineRequete("545345", "CNEWS","01/11/2023");
$creerLivre = new \App\UserStories\creerMagazine\CreerMagazine($entityManager,$validateur);


$executed = $creerLivre->execute($requete);

