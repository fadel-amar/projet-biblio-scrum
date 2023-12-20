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
        // création magazine
        $magazine = (new \JeuDonnee())->creerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->creerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);
        // creation emprunt
        $emprunt = (new \JeuDonnee())->creerEmprunt($this->entityManager, $magazine, $adherent);

        $this->entityManager->flush();


        $requete = new RetourEmpruntRequete("EM-000000001");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);
        $execute = $retourEmprunt->excute($requete);


        self::assertTrue($execute);

        // récupérer enregistrement
        $empruntRecup = $this->entityManager->getRepository(Emprunt::class)->find(1);
        $mediaRecup = $this->entityManager->getRepository(Media::class)->find(1);

        self::assertEquals(Status::STATUS_DISPONIBLE, $mediaRecup->getStatus());
        self::assertEquals($emprunt->getDateRetour() , $empruntRecup->getDateRetour());

    }

    #[test]
    public function RetourEmprunt_NumeroEmpruntNonRensigne_Exception()

    {
        // création magazine
        $livre = (new \JeuDonnee())->creerLivre($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->creerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($livre);
        // creation emprunt
        $emprunt = (new \JeuDonnee())->creerEmprunt($this->entityManager, $livre, $adherent);

        $this->entityManager->flush();

        $requete = new RetourEmpruntRequete("");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);

        $this->expectExceptionMessage("Le numéro d'emprunt est obligatoire");
        $execute = $retourEmprunt->excute($requete);



    }
    #[test]
    public function RetourEmprunt_NumeroEmpruntInexistant_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->creerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->creerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);
        // creation emprunt
        $emprunt = (new \JeuDonnee())->creerEmprunt($this->entityManager, $magazine, $adherent);

        $this->entityManager->flush();

        $requete = new RetourEmpruntRequete("EM-999999998");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);

        $this->expectExceptionMessage("Le numero d'emprunt n'existe pas");
        $execute = $retourEmprunt->excute($requete);

    }

    #[test]
    public function RetourEmprunt_NumeroEmpruntInvalide_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->creerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->creerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);
        // creation emprunt
        $emprunt = (new \JeuDonnee())->creerEmprunt($this->entityManager, $magazine, $adherent);

        $this->entityManager->flush();


        $requete = new RetourEmpruntRequete("EM-000001");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);


        $this->expectExceptionMessage("Le numero d'emprunt est invalide");
        $execute = $retourEmprunt->excute($requete);

    }

    #[test]
    public function RetourEmprunt_EmrpruntRestitue_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->creerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->creerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);
        // creation emprunt
        $emprunt = (new \JeuDonnee())->creerEmprunt($this->entityManager, $magazine, $adherent);
        $emprunt->setDateRetour(new \DateTime());

        $this->entityManager->flush();

        $requete = new RetourEmpruntRequete("EM-000000001");
        $retourEmprunt = new RetourEmprunt($this->entityManager, $this->validateur);

        $this->expectExceptionMessage("L'emprunt a déjà eté restitué");
        $execute = $retourEmprunt->excute($requete);



    }



}