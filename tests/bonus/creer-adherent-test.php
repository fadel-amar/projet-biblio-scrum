<?php

require 'bootstrap.php';
require 'vendor/autoload.php';


$adherant = new \App\Entity\Adherant();
$adherant->setNumeroAdherant('AD-145614');
$adherant->setNom('Bob');
$adherant->setPrenom('Sandra');
$adherant->setEmail('sb@gmail.com');
$adherant->setDateAdhesion(new DateTime());


$entityManager->persist($adherant);
$entityManager->flush();