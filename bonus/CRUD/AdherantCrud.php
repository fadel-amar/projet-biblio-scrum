<?php


use App\Entity\Adherent;

require "bootstrap.php";
require "vendor\autoload.php";

class AdherentCrud
{

    private $entityManager;


    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function addAdherent(string $numeroAdherent, string $nom, string $prenom , string $email)
    {
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($numeroAdherent);
        $adherent->setPrenom($prenom);
        $adherent->setNom($nom);
        $adherent->setEmail($email);
        $adherent->setDateAdhesion(new DateTime());
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

    }

    public function getAll()
    {
        return $this->entityManager->getRepository(Adherent::class)->findAll();
    }


    public function deleteAdherent($idAdherent)
    {
        $adherent = $this->entityManager->getRepository(Adherent::class)->find($idAdherent);

        if ($adherent) {
            $this->entityManager->remove($adherent);
            $this->entityManager->flush();
        } else {
            echo "L'adhérent n'a pas été trouvé.";
        }
    }



}