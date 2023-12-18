<?php

namespace App\UserStories\emprunterMedia;

use App\Entity\Emprunt;
use App\Entity\Media;
use App\Entity\Adherent;
use App\Entity\Status;
use App\Services\AdhesionValide;
use App\Services\DateRetourEstime;
use App\Services\GenerateurNumeroEmprunt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmprunterMedia
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validateur;
    private GenerateurNumeroEmprunt $numeroEmprunt;

    public function __construct(EntityManagerInterface $entityManager,  GenerateurNumeroEmprunt $numeroEmprunt , ValidatorInterface $validateur )
    {
        $this->entityManager = $entityManager;
        $this->validateur = $validateur;
        $this->numeroEmprunt = $numeroEmprunt;
    }


    /**
     * @throws \Exception
     */
    public function execute( EmprunterMediaRequete $requete): bool {

        // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);

        if (count($problemes) > 0) {
            $messagesErreur = [];

            foreach ($problemes as $probleme) {
                $messagesErreur[] =  $probleme->getMessage();
            }

            throw new \Exception(implode("\n", $messagesErreur));
        }


        // Récupérer le média et l'adhérent
        $media = $this->entityManager->getRepository(Media::class)->findOneBy(['id' => $requete->idMedia]);
        $adherent = $this->entityManager->getRepository(Adherent::class)->findOneBy (['numero_adherent' => $requete->numeroAdherent]);
        $numeroEmprunt = $this->numeroEmprunt->execute();

        // todo Vérifier l'existence du média et de l'adhérent
        if (!$media) {
            throw new \Exception("Le media n'est pas enregistré dans la BDD");
        }
        if (!$adherent) {
            throw new \Exception("L'adherent n'est pas enregistré dans la BDD");
        }

        // todo Vérifier que le média est disponible
        if ($media->getStatus() !== Status::STATUS_DISPONIBLE) {
            throw new \Exception("Le media n'est pas disponible");
        }

        // todo Vérifier que le numero emprunt est unique
        $numeroEmpruntBDD = $this->entityManager->getRepository(Adherent::class)->findOneBy(['numero_adherent' => $numeroEmprunt]);
        if ($numeroEmpruntBDD != null) {
            throw new \Exception("Le numero d'emprunt existe déjà");
        }


        // todo Vérifier la validité de l'adhésion de l'adhérent

        if (!$adherent->AdhesionValide()) {
            throw new \Exception("L'adhésion de l'adhérent n'est plus valide");
        }

        // Créer un nouvel emprunt
        $emprunt = new Emprunt();
        $emprunt->setAdherent($adherent);
        $emprunt->setMedia($media);
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setNumeroEmprunt($numeroEmprunt);

        $dateRetourEstime = $emprunt->calculDateRetourEstime($emprunt->getDateEmprunt(), $media->getDureeEmprunt());
        $emprunt->setDateRetourEstime($dateRetourEstime);

        $this->entityManager->persist($emprunt);
        $media->setStatus(Status::STATUS_EMPRUNT);
        $this->entityManager->flush();
        return true;

    }

}

