<?php


namespace Tests\Integrations\UserStories;

use App\Entity\Adherent;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories;
use App\UserStories\creerAdherant\CreerAdherent;
use App\UserStories\creerAdherant\CreerAdherentRequete;
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
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;
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
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherent();
//        $this->validateur = Validation::createValidator();
        $this->validateur = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }


    #[test]
    public function creerAdherent_ValeursCorrectes_Vrai()
    {
        // Arrange
        $requete = new CreerAdherentRequete("jhon", "doe", "jhondoe@gmail.com");

        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $resultat = $creerAdherent->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherent);
        $this->assertEquals("jhon", $adherent->getPrenom());
        $this->assertEquals("doe", $adherent->getNom());
    }

    #[test]
    public function creerAdherent_PrenomNonforuni_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete("", "doe", "jhondoe@gmail.com");

        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $this->expectExceptionMessage("Le prenom est obligatoire");
        $resultat = $creerAdherent->execute($requete);
        // Assert
    }

    #[test]
    public function creerAdherent_NomNonforuni_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete("jhon", "", "jhondoe@gmail.com");

        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $this->expectExceptionMessage("Le nom est obligatoire");
        $resultat = $creerAdherent->execute($requete);
        // Assert
    }


    #[test]
    public function creerAdherent_AdresseMailPasEncoreInscrite_Vraie()
    {
        // Arrange
        $requete = new CreerAdherentRequete("jhon", "doe", "jhondoe@gmail.com");

        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $resultat = $creerAdherent->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherent);
        $this->assertEquals("jhon", $adherent->getPrenom());
        $this->assertEquals("doe", $adherent->getNom());
    }

    #[test]
    public function creerAdherent_AdresseMailNonUnique_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete("jhon", "doe", "jhondoe@gmail.com");
        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);


        // Act

        $resultat = $creerAdherent->execute($requete);
        $this->expectExceptionMessage("L'email est déjà inscrit à un adherent");
        $resultat = $creerAdherent->execute($requete);

        // Assert

    }

    #[test]
    public function creerAdherent_NumeroAdherentNonUniqueDansLaBDD_Exception()
    {
        // Arrange

        $MockGenerateurNumeroAdherent = $this->createMock(GenerateurNumeroAdherent::class);
        $MockGenerateurNumeroAdherent->method("generer")->willReturn('AD-456746');

        $requete1 = new CreerAdherentRequete("jhon", "doe", "jhondoe@gmail.com");
        $creerAdherent1 = new CreerAdherent($this->entityManager, $MockGenerateurNumeroAdherent, $this->validateur);

        $requete2 = new CreerAdherentRequete("nono", "kolo", "kolo@gmail.com");
        $creerAdherent2 = new CreerAdherent($this->entityManager, $MockGenerateurNumeroAdherent, $this->validateur);


        // Act
        $resultat = $creerAdherent1->execute($requete1);
        $this->expectExceptionMessage("Le numero adherent existe déjà");
        $resultat = $creerAdherent2->execute($requete2);


        // Assert

    }

    #[test]
    public function creerAdherent_NumeroAdherentUnique_Vraie()
    {
// Arrange
        $requete = new CreerAdherentRequete("jhon", "doe", "jhondoe@gmail.com");

        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        // Act

        $resultat = $creerAdherent->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherent);
        $this->assertEquals("jhon", $adherent->getPrenom());
        $this->assertEquals("doe", $adherent->getNom());

    }


}