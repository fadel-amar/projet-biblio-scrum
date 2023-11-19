<?php

namespace App\UserStories\creerAdherant;


use App\Entity\Adherent;
use App\Services\GenerateurNumeroAdherent;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerAdherent
{
    // Dépendances
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroAdherent $generateurNumeroAdherent;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GenerateurNumeroAdherent $generateurNumeroAdherent
     * @param ValidatorInterface $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, GenerateurNumeroAdherent $generateurNumeroAdherent, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->generateurNumeroAdherent = $generateurNumeroAdherent;
        $this->validateur = $validateur;
    }


    /**
     * @throws \Exception
     */
    public function execute(\App\UserStories\creerAdherant\CreerAdherentRequete $requete): bool
    {

        // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);

        if (count($problemes) > 0) {
            $messagesErreur = [];

            foreach ($problemes as $probleme) {
                $messagesErreur[] =  $probleme->getMessage();
            }

            throw new \Exception(implode("\n", $messagesErreur));
        }


        // todo Test Vérifier que l'email n'existe pas déjà
        $getEmailAdherent = $this->entityManager->getRepository(Adherent::class)->findOneBy(['email' => $requete->email]);
        if ($getEmailAdherent != null) {
            throw new \Exception("L'email est déjà inscrit à un adherent");
        }

        // Générer un numéro d'adhérent au format AD-999999
        $numeroAdherent = $this->generateurNumeroAdherent->generer();

        // todo Test Vérifier que le numéro n'existe pas déjà
        $getNumeroAdherent = $this->entityManager->getRepository(Adherent::class)->findOneBy(['numeroAdherent' => $numeroAdherent]);
        if ($getNumeroAdherent != null) {
            throw new \Exception("Le numero adherent existe déjà");
        }

        // Créer l'adhérent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($numeroAdherent);
        $adherent->setEmail($requete->email);
        $adherent->setNom($requete->nom);
        $adherent->setPrenom($requete->prenom);
        $adherent->setDateAdhesion(new \DateTime());

        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        return true;
    }
}