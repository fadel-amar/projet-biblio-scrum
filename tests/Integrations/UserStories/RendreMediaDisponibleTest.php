<?php

namespace Tests\Integrations\UserStories;

use App\Entity\Livre;
use App\Entity\Magazine;
use App\Entity\Media;
use App\Entity\Status;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories\creerLivre\CreerLivre;
use App\UserStories\creerLivre\CreerLivreRequete;
use App\UserStories\creerMagazine\CreerMagazine;
use App\UserStories\creerMagazine\CreerMagazineRequete;
use App\UserStories\listerNouveauxMedias\ListerNouveauxMedias;
use App\UserStories\rendreMediaDisponible\RendreMediaDisponible;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\assertEquals;

class RendreMediaDisponibleTest extends TestCase
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
    public function RendreMediaDisponible_MediaStatutNouveauAndValeursCorrect_Vrai(){


        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);
        $execute = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute($magazine->getId());

        assertEquals(Status::STATUS_DISPONIBLE, $magazine->getStatus());
        self::assertTrue($execute);
    }

    #[test]
    public function RendreMediaDisponible_MediaStatutDisponible_Exception()
    {

        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);

        $execute = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute($magazine->getId());

        $this->expectExceptionMessage("Le media est déjà disponible");

        $execute2 = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute($magazine->getId());

        assertEquals(Status::STATUS_DISPONIBLE, $magazine->getStatus());
    }
    #[test]
    public function RendreMediaDisponible_idMediaExistePAS_Exception()
    {

        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);

        $this->expectExceptionMessage("Le média avec l'ID fourni n'a pas été trouvé");
        $execute = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute(3);

        self::assertNotEquals(Status::STATUS_DISPONIBLE, $magazine->getSatus());

    }

    #[test]
    public function RendreMediaDisponible_MediaStatutDifferentDeNouveau_Exception()
    {


        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);
        $magazine->setStatus(Status::STATUS_EMPRUNT);

        $this->expectExceptionMessage("Seul un média ayant le statut “Nouveau” peut-être rendu disponible");

        $execute = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute($magazine->getId());
        self::assertNotEquals(Status::STATUS_DISPONIBLE, $magazine->getSatus());

    }

    #[test]
    public function RendreMediaDisponible_idMediaNull_Exception()
    {

        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);

        $this->expectExceptionMessage("L'id media est obligatoire");
        $execute = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute(null);

        self::assertNotEquals(Status::STATUS_DISPONIBLE, $magazine->getSatus());

    }


}
