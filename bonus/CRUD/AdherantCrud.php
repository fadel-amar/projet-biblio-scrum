<?php


use App\Entity\Adherant;

require "bootstrap.php";
require "vendor\autoload.php";

class AdherantCrud
{

    private $entityManager;


    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function addAdherant(string $numeroAdherent, string $nom, string $prenom , string $email)
    {
        $adherant = new Adherant();
        $adherant->setNumeroAdherant($numeroAdherent);
        $adherant->setPrenom($prenom);
        $adherant->setNom($nom);
        $adherant->setEmail($email);
        $adherant->setDateAdhesion(new DateTime());
        $this->entityManager->persist($adherant);
        $this->entityManager->flush();

    }

    public function getAll()
    {
        return $this->entityManager->getRepository(Adherant::class)->findAll();
    }


    public function deleteAdherant($idAdherent)
    {
        $adherent = $this->entityManager->getRepository(Adherant::class)->find($idAdherent);

        if ($adherent) {
            $this->entityManager->remove($adherent);
            $this->entityManager->flush();
        } else {
            echo "L'adhérent n'a pas été trouvé.";
        }
    }



}