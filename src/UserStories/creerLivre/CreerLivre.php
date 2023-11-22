<?php

namespace App\UserStories\creerLivre;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Integrations\UserStories\CreerLivreTest;

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

    public function execute(creerLivreRequete $requete)
    {
        // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);

        if (count($problemes) > 0) {
            $messagesErreur = [];

            foreach ($problemes as $probleme) {
                $messagesErreur[] = $probleme->getMessage();
            }

            throw new \Exception(implode("<br\>", $messagesErreur));
        }

        // Verifier que l' isbn ets unique
        $isbn = $requete->isbn;
        $isbnDB = $this->entityManager->getRepository(Livre::class)->findOneBy(['isbn' => $isbn]);
        if ($isbnDB) {
            throw new \Exception("L'isbn n'est pas unique");
        }



    }


}