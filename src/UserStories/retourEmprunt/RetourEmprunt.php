<?php

namespace App\UserStories\retourEmprunt;

use App\Entity\Emprunt;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RetourEmprunt
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validateur;

    public function __construct(EntityManagerInterface $entityManager , ValidatorInterface $validateur )
    {
        $this->entityManager = $entityManager;
        $this->validateur = $validateur;
    }


    /**
     * @throws \Exception
     */
    public function excute (RetourEmpruntRequete $requete) : bool {
       // Valider les données ede la requête

        $problemes = $this->validateur->validate($requete);
        if (count($problemes) > 0) {
            $messagesErreur = [];

            foreach ($problemes as $probleme) {
                $messagesErreur[] =  $probleme->getMessage();
            }

            throw new \Exception(implode("\n", $messagesErreur));
        }

        if(!preg_match("/^EM-\d{9}$/",$requete->numeroEmprunt , )) {
            throw new \Exception("Le numero d'emprunt est invalide");
        }





        $emprunt = $this->entityManager->getRepository(Emprunt::class)->findOneBy(['numeroEmprunt'=> $requete->numeroEmprunt]);


        // todo vérifier si le numéro emprunt existe
        if(!$emprunt) {
            throw new \Exception("Le numero d'emprunt n'existe pas");
        }

        // todo verifier si l'emprunt ne doit pas voir été restitué

        if($emprunt->getDateRetour() != null) {
            throw new \Exception("L'emprunt a déjà eté restitué");
        }


        // todo vérifier si les donnés son bien enregistrés dans la BDD

        $this->entityManager->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $emprunt->setDateRetour(new \DateTime());
            $media = $emprunt->getMedia();
            $media->setStatus(Status::STATUS_DISPONIBLE);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return true;

    }




}