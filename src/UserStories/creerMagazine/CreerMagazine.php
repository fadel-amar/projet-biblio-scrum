<?php
namespace App\UserStories\creerMagazine;



use App\Entity\Magazine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerMagazine
{

    private const STATUS_NOUVEAU = "Nouveau";
    private const DUREE_EMPRUNT = 10;


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

            throw new \Exception(implode("<br\>", $messagesErreur));
        }


        // Créer Magazine

        $magazine = new Magazine();
        $magazine->setNumero($requete->numero);
        $magazine->setDatePublication($requete->datePublication);
        $magazine->setTitre($requete->titre);
        $magazine->setDateCreation($requete->dateCreation);
        $magazine->setStatus(self::STATUS_NOUVEAU);
        $magazine->setDureeEmprunt(self::DUREE_EMPRUNT);
        // Enregistrer dans la BDD
        $this->entityManager->persist($magazine);
        $this->entityManager->flush();

        return true;


    }


}