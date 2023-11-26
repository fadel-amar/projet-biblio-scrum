<?php
require "./bootstrap.php";

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ValidatorBuilder;


$validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
$creerLivre = new \App\UserStories\creerLivre\CreerLivre($entityManager,$validateur);
$requete = new \App\UserStories\creerLivre\creerLivreRequete("67324", "Victor","Les chevaliers", "07/10/2023",120);


$executed = $creerLivre->execute($requete);

