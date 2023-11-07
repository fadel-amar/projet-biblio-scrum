<?php

namespace App\UserStories\CreerAdherent;


use App\Entity\Adherant;
use App\Services\GenerateurNumeroAdherant;
use App\UserStories\CreerAdherantRequete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerAdherant {
    // Dépendances
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroAdherant $generateurNumeroAdherent;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GenerateurNumeroAdherant $generateurNumeroAdherent
     * @param $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, GenerateurNumeroAdherant $generateurNumeroAdherent, $validateur)
    {
        $this->entityManager = $entityManager;
        $this->generateurNumeroAdherent = $generateurNumeroAdherent;
        $this->validateur = $validateur;
    }


    public function execute( CreerAdherantRequete $requete): bool {

        // Valider les données en entrées (de la requête)


        // Vérifier que l'email n'existe pas déjà

        // Générer un numéro d'adhérent au format AD-999999
        $numeroAdherant = $this->generateurNumeroAdherent->generer();

        // Vérifier que le numéro n'existe pas déjà





        // Créer l'adhérent
        $adherant = new Adherant();
        $adherant->setNumeroAdherant($numeroAdherant);
        $adherant->setEmail($requete->email);
        $adherant->setNom($requete->nom);
        $adherant->setPrenom($requete->prenom);
        $adherant->setDateAdhesion(new \DateTime());

        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherant);
        $this->entityManager->flush();

        return true;
    }
}