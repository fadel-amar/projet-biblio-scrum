<?php

namespace App\UserStories\emprunterMedia;

use App\Entity\Emprunt;
use App\Entity\Media;
use App\Entity\Adherent;
use App\Entity\Status;
use App\Services\DateRetourEstime;
use App\Services\GenerateurNumeroEmprunt;
use Doctrine\ORM\EntityManagerInterface;

class EmprunterMedia
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function execute(int $mediaId, string $adherentNumero): bool
    {
        // Récupérer le média et l'adhérent
        $media = $this->entityManager->getRepository(Media::class)->find($mediaId);
        $adherent = $this->entityManager->getRepository(Adherent::class)->findOneBy (['numero_adherent' => $adherentNumero]);
        $numeroEmprunt = (new GenerateurNumeroEmprunt($this->entityManager))->execute();

        // Vérifier l'existence du média et de l'adhérent
        if (!$media) {
            throw new \Exception("Media non trouvé.");
        }
        if (!$adherent) {
            throw new \Exception("Adhérent non trouvé.");
        }

        // Vérifier que le média est disponible
        if ($media->getStatus() !== Status::STATUS_DISPONIBLE) {
            throw new \Exception("Le média n'est pas disponible.");
        }

        // Vérifier que le numero emprunt est unique
        $numeroEmpruntBDD = $this->entityManager->getRepository(Adherent::class)->findOneBy(['numero_adherent' => $numeroEmprunt]);
        if ($numeroEmpruntBDD != null) {
            throw new \Exception("Le numero d'emprunt existe déjà");
        }


/*
        // Vérifier la validité de l'adhésion de l'adhérent
        if (!$adherent->isAdhesionValide()) {
            throw new \Exception("L'adhésion de l'adhérent n'est pas valide.");
        }*/

        // Créer un nouvel emprunt
        $emprunt = new Emprunt();
        $emprunt->setAdherent($adherent);
        $emprunt->setMedia($media);
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setNumeroEmprunt($numeroEmprunt);

        $dateRetourEstime = (new DateRetourEstime())->excute($emprunt->getDateEmprunt(), $media->getDureeEmprunt());
        $emprunt->setDateRetourEstime($dateRetourEstime);

        $this->entityManager->persist($emprunt);
        $media->setStatus(Status::STATUS_EMPRUNT);
        $this->entityManager->flush();
        return true;

    }

}

