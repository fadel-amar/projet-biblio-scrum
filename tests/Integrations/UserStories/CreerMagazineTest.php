<?php


namespace Tests\Integrations\UserStories;

use App\Entity\Magazine;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\assertNotNull;


class CreerMagazineTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validateur;


    /**
     * @throws MissingMappingDriverImplementation
     * @throws ToolsException
     * @throws Exception
     */
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
    public function creerMagazine_ValeursCorrectes_Vrai()
    {

        // Arrange
        $requete = new UserStories\creerMagazine\CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");

        $creerMagzine = new UserStories\creerMagazine\CreerMagazine($this->entityManager, $this->validateur);
        // Act

        $resultat = $creerMagzine->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);
        assertNotNull($magazine);

    }

    #[test]
    public function creerMagzine_NumeroNonFourni_Exception()
    {
        // Arrange
        $requete = new UserStories\creerMagazine\CreerMagazineRequete("", "Top Ligue", "12/07/2023");

        $creerMagzine = new UserStories\creerMagazine\CreerMagazine($this->entityManager, $this->validateur);
        // Act

        $this->expectExceptionMessage("Le numero de magazine est obligatoire");

        $resultat = $creerMagzine->execute($requete);

    }

    #[test]
    public function creerMagzine_DatePublicationNonFourni_Exception()
    {
        // Arrange
        $requete = new UserStories\creerMagazine\CreerMagazineRequete(23454, "Top Ligue", "");

        $creerMagzine = new UserStories\creerMagazine\CreerMagazine($this->entityManager, $this->validateur);
        // Act

        $this->expectExceptionMessage("La date de publication est obligatoire");

        $resultat = $creerMagzine->execute($requete);

    }

    #[test]
    public function creerMagzine_TitreNonFourni_Exception()
    {
        // Arrange
        $requete = new UserStories\creerMagazine\CreerMagazineRequete(23454, "", "12/07/2023");

        $creerMagzine = new UserStories\creerMagazine\CreerMagazine($this->entityManager, $this->validateur);
        // Act

        $this->expectExceptionMessage("Le titre est obligatoire");

        $resultat = $creerMagzine->execute($requete);

    }





}