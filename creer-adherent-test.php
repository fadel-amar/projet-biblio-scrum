<?php

require 'bootstrap.php';
require 'vendor/autoload.php';


$adherent = new \App\Entity\Adherent();
$adherent->setNumeroAdherent('AD-145614');
$adherent->setNom('Bob');
$adherent->setPrenom('Sandra');
$adherent->setEmail('sb@gmail.com');
$adherent->setDateAdhesion(new DateTime());


$entityManager->persist($adherent);
$entityManager->flush();