<?php
namespace App\UserStories\creerMagazine;



use App\Entity\DureeEmprunt;
use App\Entity\Magazine;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerMagazine
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

    public function execute(CreerMagazineRequete $requete): bool
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


        // Créer Magazine
        $magazine = new Magazine();
        $magazine->setNumero($requete->numero);
        $magazine->setDatePublication($requete->datePublication);
        $magazine->setTitre($requete->titre);
        $magazine->setDateCreation(new \DateTime());
        $magazine->setStatus(Status::STATUS_NOUVEAU);
        $magazine->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_MAGAZINE);
        // Enregistrer dans la BDD
        $this->entityManager->persist($magazine);
        $this->entityManager->flush();

        return true;


    }


}