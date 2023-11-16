<?php


namespace Integrations\UserStories;

use App\Entity\Adherant;
use App\Services\GenerateurNumeroAdherant;
use App\UserStories\CreerAdherantRequete;
use App\UserStories\CreerAdherent\CreerAdherant;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CreerAdherentTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected GenerateurNumeroAdherant $generateurNumeroAdherent;
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
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherant();
        $this->validateur = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }


    #[test]
    public function creerAdherent_ValeursCorrectes_True()
    {
        // Arrange
        $requete = new CreerAdherantRequete("jhon", "doe", "jhondoe@gmail.com");

        $creerAdherant = new CreerAdherant($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $resultat = $creerAdherant->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherant::class);
        $adherant = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherant);
        $this->assertEquals("jhon", $adherant->getPrenom());
        $this->assertEquals("doe", $adherant->getNom());
    }

    #[test]
    public function creerAdherent_PrenomNonforuni_Exception()
    {
        // Arrange
        $requete = new CreerAdherantRequete("", "doe", "jhondoe@gmail.com");

        $creerAdherant = new CreerAdherant($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $this->expectExceptionMessage("Le prenom est obligatoire");
        $resultat = $creerAdherant->execute($requete);
        // Assert
    }

    #[test]
    public function creerAdherent_NomNonforuni_Exception()
    {
        // Arrange
        $requete = new CreerAdherantRequete("jhon", "", "jhondoe@gmail.com");

        $creerAdherant = new CreerAdherant($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $this->expectExceptionMessage("Le nom est obligatoire");
        $resultat = $creerAdherant->execute($requete);
        // Assert
    }


    #[test]
    public function creerAdherent_AdresseMailPasEncoreInscrite_Vraie()
    {
        // Arrange
        $requete = new CreerAdherantRequete("jhon", "doe", "jhondoe@gmail.com");

        $creerAdherant = new CreerAdherant($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $resultat = $creerAdherant->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherant::class);
        $adherant = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherant);
        $this->assertEquals("jhon", $adherant->getPrenom());
        $this->assertEquals("doe", $adherant->getNom());
    }

    #[test]
    public function creerAdherent_AdresseMailNonUnique_Exception()
    {
        // Arrange
        $requete = new CreerAdherantRequete("jhon", "doe", "jhondoe@gmail.com");
        $creerAdherant = new CreerAdherant($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);


        // Act

        $resultat = $creerAdherant->execute($requete);
        $this->expectExceptionMessage("L'email est déjà inscrit à un adherant");
        $resultat = $creerAdherant->execute($requete);

        // Assert

    }

    #[test]
    public function creerAdherent_NumeroAdherantNonUniqueDansLaBDD_Exception()
    {
        // Arrange

        $MockGenerateurNumeroAdherant = $this->createMock(GenerateurNumeroAdherant::class);
        $MockGenerateurNumeroAdherant->method("generer")->willReturn('AD-456746');

        $requete1 = new CreerAdherantRequete("jhon", "doe", "jhondoe@gmail.com");
        $creerAdherant1 = new CreerAdherant($this->entityManager, $MockGenerateurNumeroAdherant, $this->validateur);

        $requete2 = new CreerAdherantRequete("nono", "kolo", "kolo@gmail.com");
        $creerAdherant2 = new CreerAdherant($this->entityManager, $MockGenerateurNumeroAdherant, $this->validateur);


        // Act
        $resultat = $creerAdherant1->execute($requete1);
        $this->expectExceptionMessage("Le numero adherant existe déjà");
        $resultat = $creerAdherant2->execute($requete2);


        // Assert

    }

    #[test]
    public function creerAdherent_NumeroAdherantUnique_Vraie()
    {
// Arrange
        $requete = new CreerAdherantRequete("jhon", "doe", "jhondoe@gmail.com");

        $creerAdherant = new CreerAdherant($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $resultat = $creerAdherant->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherant::class);
        $adherant = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherant);
        $this->assertEquals("jhon", $adherant->getPrenom());
        $this->assertEquals("doe", $adherant->getNom());

    }


}