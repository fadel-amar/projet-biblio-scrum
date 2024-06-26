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

        if (!$media) {
            throw new \Exception("Le Media renseigné n'a pas été trouvé");
        }
        if (!$adherent) {
            throw new \Exception("L'adherent renseigné n'a pas été trouvé");
        }

        if ($media->getStatus() !== Status::STATUS_DISPONIBLE) {
            throw new \Exception("Le media n'est pas disponible");
        }

        $numeroEmpruntBDD = $this->entityManager->getRepository(Emprunt::class)->findOneBy(['numeroEmprunt' => $numeroEmprunt]);
        if ($numeroEmpruntBDD != null) {
            throw new \Exception("Le numero d'emprunt existe déjà");
        }


        if (!$adherent->AdhesionValide()) {
            throw new \Exception("L'adhésion de l'adhérent n'est plus valide veuillez renouvelez l'adhésion");
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

