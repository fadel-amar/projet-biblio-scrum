<?php


namespace Tests\Integrations\UserStories;

use App\Entity\Livre;
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


class CreerLivreTest extends TestCase
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
    public function creerLivre_ValeursCorrectes_Vrai()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("648-46465", "victor", "Le chevaleir", "12/12/2022", 120);

        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act

        $resultat = $creerLivre->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Livre::class);
        $livre = $repository->findOneBy(['isbn' => '648-46465']);
        assertNotNull($livre);

    }

    #[test]
    public function creerLivre_IsbnNonFourni_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("", "victor", "Le chevaleir", "12/12/2022", 120);

        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act
        $this->expectExceptionMessage("L'isbn est obligatoire");

        $resultat = $creerLivre->execute($requete);
        // Assert
    }

    #[test]
    public function creerLivre_IsbnNonUnique_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("476-8769", "victor", "Le chevaleir", "12/12/2022", 120);
        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        $creerLivre->execute($requete);

        // Act
        $this->expectExceptionMessage("L'isbn n'est pas unique");
        $resultat = $creerLivre->execute($requete);
        // Assert
    }

    #[test]
    public function creerLivre_TitreNonFourni_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("4325-356", "victor", "", "12/12/2022", 120);

        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act
        $this->expectExceptionMessage("Le titre est obligatoire");

        $resultat = $creerLivre->execute($requete);
        // Assert
    }

    #[test]
    public function creerLivre_DateCreationNonFourni_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("4325-356", "victor", "Les chevaliers", "", 120);

        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act
        $this->expectExceptionMessage("La date de parution est obligatoire");

        $resultat = $creerLivre->execute($requete);
    }


    #[test]
    public function creerLivre_AuteurNonFourni_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("4325-356", "", "Les chevaliers", "12/06/1999", 120);

        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act
        $this->expectExceptionMessage("L'auteur est obligatoire");

        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NombrePagesNegatif_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("4325-356", "Victor", "Les chevaliers", "12/06/1999", -1);
        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act
        $this->expectExceptionMessage("Le nombre de pages doit être supérieur à 0");

        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NombrePagesNonFourni_Exception()
    {

        // Arrange
        $requete = new UserStories\creerLivre\creerLivreRequete("4325-356", "Victor", "Les chevaliers", "12/06/1999");
        $creerLivre = new UserStories\creerLivre\CreerLivre($this->entityManager, $this->validateur);
        // Act
        $this->expectExceptionMessage("Le nombre de pages est obligatoire");

        $resultat = $creerLivre->execute($requete);
    }


}