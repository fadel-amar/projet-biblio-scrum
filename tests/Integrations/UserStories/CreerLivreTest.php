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


class CreerLivreTest extends TestCase
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
    public function creerLivre_ValeursCorrectes_Vrai () {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("648-46465", "victor", 260);

        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager,  $this->validateur);
        // Act
/*
        $resultat = $creerAdherent->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(["email" => "jhondoe@gmail.com"]);
        $this->assertNotNull($adherent);
        $this->assertEquals("jhon", $adherent->getPrenom());
        $this->assertEquals("doe", $adherent->getNom());*/



    }







}