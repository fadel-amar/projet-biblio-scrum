<?php

namespace App\UserStories\listerNouveauxMedias;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            $medias [] = $mediaRepo;
        }
        return $medias;
    }

}