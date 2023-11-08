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
}