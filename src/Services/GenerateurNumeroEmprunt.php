<?php

namespace App\Services;

use App\Entity\Emprunt;
use Doctrine\ORM\EntityManagerInterface;

class GenerateurNumeroEmprunt
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): string
    {

        // Récupérer le dernier ID inséré

        $dernierEmprunt  = $this->entityManager->getRepository(Emprunt::class)->findBy([], ['id' => 'DESC']);

        if($dernierEmprunt!= null) {

            $dernierId = $dernierEmprunt[0]->getId();
            $idFormatted = str_pad($dernierId+1, 9, '0', STR_PAD_LEFT);

        } else {

            // Formater l'ID avec des zéros à gauche
            $idFormatted = str_pad(0, 9, '0', STR_PAD_LEFT);
        }

        $numeroEmprunt = 'EM-' . $idFormatted;

        return $numeroEmprunt;
    }

}

