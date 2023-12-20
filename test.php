<?php
require "bootstrap.php";
use Symfony\Component\Validator\ValidatorBuilder;


$validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

$requete = new \App\UserStories\retourEmprunt\RetourEmpruntRequete("EM-000000001");
$retourEmorunt = New \App\UserStories\retourEmprunt\RetourEmprunt($entityManger, $validateur );

$retourEmorunt->excute($requete);



