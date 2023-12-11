<?php

namespace App\UserStories\listerNouveauxMedias;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

class ListerNouveauxMedias {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): ?array {
        $mediasRepo = $this->entityManager->getRepository(\App\Entity\Media::class)->findBy(['status' => \App\Entity\Status::STATUS_NOUVEAU], ['dateCreation' => 'DESC']);
        $medias = [];
        foreach ($mediasRepo as $mediaRepo) {
            $medias[] = ['id' => $mediaRepo->getId(), 'titre' => $mediaRepo->getTitre(), 'status' => $mediaRepo->getStatus(),
                'dateCreation' => $mediaRepo->getDateCreation(), 'type' => (new ReflectionClass($mediaRepo))->getShortName()];
        }
        return $medias;
    }

}