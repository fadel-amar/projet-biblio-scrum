<?php

namespace Tests\Integrations\UserStories;

use App\Entity\Adherent;
use App\Entity\DureeEmprunt;
use App\Entity\Emprunt;
use App\Entity\Magazine;
use App\Entity\Media;
use App\Entity\Status;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\retourEmprunt\RetourEmprunt;
use App\UserStories\retourEmprunt\RetourEmpruntRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\assertEquals;

class RetourEmpruntTest extends TestCase
{


    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validateur;




    protected function setUp(): void
    {
        echo "setup ---------------------------------------------------------";
        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__ . '/../../../src/'],
            true
        );

        // Configuration de la connexion à la base de données
        // Utilisation d'une base de données SQLite en mémoire
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);

        // Création des dépendances
        $this->entityManager = new EntityManager($connection, $config);
        $this->validateur = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
    #[test]
    public function RetourEmprunt_ValeursCorrectes_Vrai()
    {
        // creation $media
        $magazine = new Magazine();
        $magazine->setTitre('JMola');
        $magazine->setNumero("545641561");
        $magazine->setDatePublication("12/12/2023");
        $magazine->setDateCreation(new \DateTime());
        $magazine->setStatus(Status::STATUS_DISPONIBLE);
        $magazine->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_MAGAZINE);
        $this->entityManager->persist($magazine);

        // création adherent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent("AD-456444");
        $adherent->setNom("Jean");
        $adherent->setPrenom("Louis");
        $adherent->setEmail("jl@ytest.fr");
        $adherent->setDateAdhesion(new \DateTime());
        $this->entityManager->persist($adherent);

        //Creation emprunt
        $emprunt = new Emprunt();
        $emprunt->setMedia($magazine);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateRetourEstime(new \DateTime("2024-01-12"));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setNumeroEmprunt("EM-000000001");
        $this->entityManager->persist($emprunt);


        $this->entityManager->flush();


        $requete = new RetourEmpruntRequete("EM-000000001");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);
        $execute = $retourEmprunt->excute($requete);


        self::assertTrue($execute);

        // récupérer enregistrement
        $empruntRecup = $this->entityManager->getRepository(Emprunt::class)->find(1);
        $mediaRecup = $this->entityManager->getRepository(Media::class)->find(1);

        self::assertEquals(Status::STATUS_DISPONIBLE, $mediaRecup->getStatus());
        self::assertEquals($emprunt->getDateRetour(), $empruntRecup->getDateRetour());
        self::assertEquals($emprunt->getAdherent() , $empruntRecup->getAdherent());
        self::assertEquals($emprunt->getMedia(), $empruntRecup->getMedia());
        self::assertEquals($emprunt->getDateEmprunt() , $empruntRecup->getDateEmprunt());
        self::assertEquals($emprunt->getId(), $emprunt->getId());
        self::assertEquals($emprunt->getDateRetourEstime(), $empruntRecup->getDateRetourEstime());

    }

    #[test]
    public function RetourEmprunt_NumeroEmpruntNonRensigne_Exception()

    {
        // creation $media
        $magazine = new Magazine();
        $magazine->setTitre('JMola');
        $magazine->setNumero("545641561");
        $magazine->setDatePublication("12/12/2023");
        $magazine->setDateCreation(new \DateTime());
        $magazine->setStatus(Status::STATUS_DISPONIBLE);
        $magazine->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_MAGAZINE);
        $this->entityManager->persist($magazine);

        // création adherent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent("AD-456444");
        $adherent->setNom("Jean");
        $adherent->setPrenom("Louis");
        $adherent->setEmail("jl@ytest.fr");
        $adherent->setDateAdhesion(new \DateTime());
        $this->entityManager->persist($adherent);

        //Creation emprunt
        $emprunt = new Emprunt();
        $emprunt->setMedia($magazine);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateRetourEstime(new \DateTime("2024-01-12"));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setNumeroEmprunt("EM-999999999");
        $emprunt->setDateRetour(new \DateTime());
        $this->entityManager->persist($emprunt);


        $this->entityManager->flush();


        $requete = new RetourEmpruntRequete("");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);

        $this->expectExceptionMessage("Le numéro d'emprunt est obligatoire");
        $execute = $retourEmprunt->excute($requete);



    }
    #[test]
    public function RetourEmprunt_NumeroEmpruntInexistant_Exception()
    {
        // creation $media
        $magazine = new Magazine();
        $magazine->setTitre('JMola');
        $magazine->setNumero("545641561");
        $magazine->setDatePublication("12/12/2023");
        $magazine->setDateCreation(new \DateTime());
        $magazine->setStatus(Status::STATUS_DISPONIBLE);
        $magazine->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_MAGAZINE);
        $this->entityManager->persist($magazine);

        // création adherent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent("AD-456444");
        $adherent->setNom("Jean");
        $adherent->setPrenom("Louis");
        $adherent->setEmail("jl@ytest.fr");
        $adherent->setDateAdhesion(new \DateTime());
        $this->entityManager->persist($adherent);

        //Creation emprunt
        $emprunt = new Emprunt();
        $emprunt->setMedia($magazine);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateRetourEstime(new \DateTime("2024-01-12"));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setNumeroEmprunt("EM-999999999");
        $emprunt->setDateRetour(new \DateTime());
        $this->entityManager->persist($emprunt);

        $requete = new RetourEmpruntRequete("EM-999999998");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);


        $this->expectExceptionMessage("Le numero d'emprunt n'existe pas");
        $execute = $retourEmprunt->excute($requete);

    }

    #[test]
    public function RetourEmprunt_NumeroEmpruntInvalide_Exception()
    {

        $requete = new RetourEmpruntRequete("EM-0000001");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);


        $this->expectExceptionMessage("Le numero d'emprunt est invalide");
        $execute = $retourEmprunt->excute($requete);

    }

    #[test]
    public function RetourEmprunt_EmrpruntRestitue_Exception()
    {
        // creation $media
        $magazine = new Magazine();
        $magazine->setTitre('JMola');
        $magazine->setNumero("545641561");
        $magazine->setDatePublication("12/12/2023");
        $magazine->setDateCreation(new \DateTime());
        $magazine->setStatus(Status::STATUS_DISPONIBLE);
        $magazine->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_MAGAZINE);
        $this->entityManager->persist($magazine);

        // création adherent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent("AD-456444");
        $adherent->setNom("Jean");
        $adherent->setPrenom("Louis");
        $adherent->setEmail("jl@ytest.fr");
        $adherent->setDateAdhesion(new \DateTime());
        $this->entityManager->persist($adherent);

        //Creation emprunt
        $emprunt = new Emprunt();
        $emprunt->setMedia($magazine);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateRetourEstime(new \DateTime("2024-01-12"));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setNumeroEmprunt("EM-999999999");
        $emprunt->setDateRetour(new \DateTime());
        $this->entityManager->persist($emprunt);


        $this->entityManager->flush();


        $requete = new RetourEmpruntRequete("EM-999999999");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);

        $this->expectExceptionMessage("L'emprunt a déjà eté restitué");
        $execute = $retourEmprunt->excute($requete);



    }



}