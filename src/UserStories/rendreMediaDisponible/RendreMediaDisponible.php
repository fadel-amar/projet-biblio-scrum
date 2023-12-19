<?php

namespace App\UserStories\rendreMediaDisponible;

use App\Entity\Media;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RendreMediaDisponible
{

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validateur;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->validateur = $validateur;

    }

    /**
     * @throws \Exception
     */
    public function execute(?int $mediaId): bool
    {
        if(!$mediaId) {
            throw new \Exception("L'id media est obligatoire");
        }
        // Vérifier que le média existe
        $media = $this->entityManager->getRepository(Media::class)->find($mediaId);
        if ($media == null) {
            throw new \Exception("Le média avec l'ID fourni n'a pas été trouvé");
        }

        if ($media->getStatus() == Status::STATUS_DISPONIBLE) {
            throw new \Exception("Le media est déjà disponible");
        }


        if ($media->getStatus() != Status::STATUS_NOUVEAU) {
            throw new \Exception("Seul un média ayant le statut “Nouveau” peut-être rendu disponible");
        }

        $media->setStatus(Status::STATUS_DISPONIBLE);
        $this->entityManager->flush();
        return true;


    }


}