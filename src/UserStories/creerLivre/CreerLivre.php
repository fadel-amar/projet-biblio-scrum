<?php

namespace App\UserStories\creerLivre;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CreerLivre
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

    public function execute(creerLivreRequete $requete) : bool
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

        // Verifier que l' isbn ets unique
        $isbn = $requete->isbn;
        $isbnDB = $this->entityManager->getRepository(Livre::class)->findOneBy(['isbn' => $isbn]);
        if ($isbnDB != null) {
            throw new \Exception("L'isbn n'est pas unique");
        }

        // Créer Livre

        $livre = new Livre();
        $livre->setIsbn($isbn);
        $livre->setAuteur($requete->auteur);
        $livre->setNbPages($requete->nbPages);
        $livre->setTitre($requete->titre);
        $livre->setDateCreation($requete->dateCreation);
        $livre->setStatus("Nouveau");
        $livre->setDureeEmprunt(21);
        // Enregistrer dans la BDD
        $this->entityManager->persist($livre);
        $this->entityManager->flush();

    return true;

    }


}