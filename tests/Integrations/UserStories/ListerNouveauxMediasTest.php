<?php

namespace Tests\Integrations\UserStories;

use App\Entity\Livre;
use App\Entity\Magazine;
use App\Entity\Media;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories\creerLivre\CreerLivre;
use App\UserStories\creerLivre\CreerLivreRequete;
use App\UserStories\creerMagazine\CreerMagazine;
use App\UserStories\creerMagazine\CreerMagazineRequete;
use App\UserStories\listerNouveauxMedias\ListerNouveauxMedias;
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

class ListerNouveauxMediasTest extends TestCase
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
    public function ListerNouveauxMedias_StatutNouveauMedia_Tableau()
    {
        // Arrange
        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        $medias = (new ListerNouveauxMedias($this->entityManager))->execute();
        self::assertIsArray($medias);
        self::assertNotEmpty($medias);
    }

    #[test]
    public function ListerNouveauxMedias_AucunMediaNouveau_TableauVide () {


        $listerNouveauxMedias = new ListerNouveauxMedias($this->entityManager);

        // Act
        $execute = $listerNouveauxMedias->execute();

        $this->assertIsArray($execute);
        $this->assertEmpty($execute);
    }

    #[test]
    public function ListerNouveauxMedias_TableauTrie_Vrai () {

        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        sleep(2);

        $requete = new creerLivreRequete("2-1234-5680-2", "victor", "Le chevaleir", 120);
        $creerLivre = new CreerLivre($this->entityManager, $this->validateur);
        $resultat = $creerLivre->execute($requete);



        $medias = (new ListerNouveauxMedias($this->entityManager))->execute();
        $execute = $medias[0]->getDateCreation() > $medias[1]->getDateCreation();

        self::assertIsArray($medias);
        self::assertNotEmpty($medias);
        self::assertTrue($execute);
    }


    #[test]
    public function ListerNouveauxMedias_TableauNonTrie_Faux () {

        $requete = new CreerMagazineRequete(66345, "Top Ligue", "12/07/2023");
        $creerMagzine = new CreerMagazine($this->entityManager, $this->validateur);
        $resultat = $creerMagzine->execute($requete);

        sleep(2);

        $requete = new creerLivreRequete("2-1234-5680-2", "victor", "Le chevaleir", 120);
        $creerLivre = new CreerLivre($this->entityManager, $this->validateur);
        $resultat = $creerLivre->execute($requete);



        $medias = (new ListerNouveauxMedias($this->entityManager))->execute();
        $execute = $medias[0]->getDateCreation() < $medias[1]->getDateCreation();

        self::assertIsArray($medias);
        self::assertNotEmpty($medias);
        self::assertFalse($execute);
    }




}

