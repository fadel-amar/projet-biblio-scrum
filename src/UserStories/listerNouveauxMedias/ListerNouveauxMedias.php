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
        $medias = $this->entityManager->getRepository(\App\Entity\Media::class)->findBy(['status' => \App\Entity\Status::STATUS_NOUVEAU], ['dateCreation' => 'DESC']);
        $mediasFiltre = [];
        foreach ($medias as $media) {
            $mediasFiltre [] = ["id" =>$media->getId(), "titre" =>$media->getTitre(),
                "statut" => $media->getStatus(),"dateCreation" =>  $media->getDateCreation()->format('d/m/Y h:i:s'), "type"=> (new ReflectionClass($media))->getShortName() ];;
        }

        return $mediasFiltre;
    }

}