<?php


namespace Tests\Integrations\UserStories;

use App\Entity\Adherent;
use App\Entity\Magazine;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories;
use App\UserStories\creerAdherant\CreerAdherent;
use App\UserStories\creerAdherant\CreerAdherentRequete;
use App\UserStories\rendreMediaDisponible\RendreMediaDisponible;
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


class EmprunterMediaTest extends TestCase
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
    public function EmprunterMedia_ValeursCorrectes_Vrai()
    {

        $requete = new UserStories\creerMagazine\CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new UserStories\creerMagazine\CreerMagazine($this->entityManager, $this->validateur);
        $creerMagzine->execute($requete);

        $requete2 = new CreerAdherentRequete("jhon", "doe", "jhondoe@gmail.com");
        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validateur);
        $creerAdherent->execute($requete2);



        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numero' => 66345]);
        $dispo = (new RendreMediaDisponible($this->entityManager, $this->validateur))->execute($magazine->getId());

        $adherent = $this->entityManager->getRepository(Adherent::class)->find(1);
        $numeroAdherent = $adherent->getNumeroAdherent();

        $EemprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager);
        $resultat = $EemprunterMedia->execute(1, $numeroAdherent);

        $this->assertTrue($resultat);

    }
}