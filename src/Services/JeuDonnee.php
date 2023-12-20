<?php

use App\Entity\DureeEmprunt;
use App\Entity\Livre;
use App\Entity\Magazine;
use App\Entity\Status;


class JeuDonnee {


    public function creerMagazine ($entityManager) {
        $magazine = new Magazine();
        $magazine->setTitre('JMola');
        $magazine->setNumero("545641561");
        $magazine->setDatePublication("12/12/2023");
        $magazine->setDateCreation(new \DateTime());
        $magazine->setStatus(Status::STATUS_NOUVEAU);
        $magazine->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_MAGAZINE);
        $entityManager->persist($magazine);
        return $magazine;
    }


    public function creerLivre($entityManager)  {
        $livre = new \App\Entity\Livre();
        $livre->setTitre("ALivre 2");
        $livre->setIsbn("978-3-16-148410-0");
        $livre->setStatus(Status::STATUS_NOUVEAU);
        $livre->setDateCreation(new DateTime());
        $livre->setNbPages(564);
        $livre->setAuteur("VIto");
        $livre->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_LIVRE);
        $entityManager->persist($livre);
        return $livre;
    }


    public function creerAdherent($entityManager)   {
        $adherent = new \App\Entity\Adherent();
        $adherent->setNumeroAdherent("AD-4465454");
        $adherent->setEmail("lm@gmail.com");
        $adherent->setPrenom("Jean");
        $adherent->setNom("Louis");
        $adherent->setDateAdhesion(new DateTime());
        $entityManager->persist($adherent);

        return $adherent;
    }

    public function creerEmprunt($entityManager, $media, $adherent)  {
        $emprunt = new \App\Entity\Emprunt();
        $emprunt->setDateEmprunt(new DateTime());
        $emprunt->setNumeroEmprunt("EM-000000001");
        $emprunt->setMedia($media);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateRetourEstime($emprunt->calculDateRetourEstime($emprunt->getDateEmprunt(), $media->getDureeEmprunt()));
        $entityManager->persist($emprunt);
        return $emprunt;
    }

    public function rendreDispoMedia (\App\Entity\Media $media) {
        $media->setStatus(Status::STATUS_DISPONIBLE);
        return $media;
    }






}

