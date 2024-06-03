<?php

namespace App\UserStories\creerBluRay;

use App\Entity\BluRay;
use App\Entity\DureeEmprunt;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerBluRay
{

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->validateur = $validateur;
    }


    public function execute(CreerBluRayRequete $requete): bool
    {
        // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);

        if (count($problemes) > 0) {
            $messagesErreur = [];

            foreach ($problemes as $probleme) {
                $messagesErreur[] = $probleme->getMessage();
            }
            throw new \Exception(implode("\n", $messagesErreur));
        }

        $bluRay = new BluRay();
        $bluRay->setTitre($requete->titre);
        $bluRay->setDuree($requete->duree);
        $bluRay->setAnneeSortie($requete->anneePublication);
        $bluRay->setRealisateur($requete->realisateur);
        $bluRay->setDateCreation(new \DateTime());
        $bluRay->setStatus(Status::STATUS_NOUVEAU);
        $bluRay->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_BLURAY);
        // Enregistrer dans la BDD
        $this->entityManager->persist($bluRay);
        $this->entityManager->flush();

        return true;
    }
}