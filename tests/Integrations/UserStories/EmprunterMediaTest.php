<?php


namespace Tests\Integrations\UserStories;

use App\Entity\Adherent;
use App\Entity\Magazine;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
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
        $this->generateurNumeroEmprunt = new GenerateurNumeroEmprunt($this->entityManager);
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherent();
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
        // création magazine
        $magazine = (new \JeuDonnee())->CreerMagazine($this->entityManager);


        // création adherent
        $adherent = (new \JeuDonnee())->CreerAdherent($this->entityManager);

        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);

        $this->entityManager->flush();

        // récupérer le numero adherent
        $adherent = $this->entityManager->getRepository(Adherent::class)->find($magazine->getId());
        $numeroAdherent = $adherent->getNumeroAdherent();

        // emprunt media
        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete($magazine->getId(), $numeroAdherent);
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);
        $resultat = $EmprunterMedia->execute($empruntMediaRequete);

        $this->assertTrue($resultat);
    }

    #[test]
    public function EmprunterMedia_AdherentNonRenseigne_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->CreerMagazine($this->entityManager);

        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);

        $this->entityManager->flush();

        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete($magazine->getId(), "");
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);

        //assert
        $this->expectExceptionMessage("Le numero adherent est obligatoire");
        $resultat = $EmprunterMedia->execute($empruntMediaRequete);
    }

    #[test]
    public function EmprunterMedia_MediaNonRenseigne_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->CreerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->CreerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);

        $this->entityManager->flush();

        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete(null, "AD-1561651");
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);

        //assert
        $this->expectExceptionMessage("L'id du media est obligatoire");
        $resultat = $EmprunterMedia->execute($empruntMediaRequete);
    }

    #[test]
    public function EmprunterMedia_AdherentExistePas_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->CreerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->CreerAdherent($this->entityManager);
        // rendre dipso le media
        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);

        $this->entityManager->flush();

        // Emprunt media
        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete($magazine->getId(), "AD-4156432");
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);

        //assert
        $this->expectExceptionMessage("L'adherent renseigné n'a pas été trouvé");
        $resultat = $EmprunterMedia->execute($empruntMediaRequete);

    }

    #[test]
    public function EmprunterMedia_MediaExistePas_Exception()
    {
        // création adherent
        $adherent = (new \JeuDonnee())->CreerAdherent($this->entityManager);
        // rendre dipso le media

        $this->entityManager->flush();

        // récupérer le numero adherent
        $adherent = $this->entityManager->getRepository(Adherent::class)->find(1);
        $numeroAdherent = $adherent->getNumeroAdherent();

        // emprunt media
        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete(1, $numeroAdherent);
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);

        $this->expectExceptionMessage("Le Media renseigné n'a pas été trouvé");
        $resultat = $EmprunterMedia->execute($empruntMediaRequete);

    }

    #[test]
    public function EmprunterMedia_MediaNonDisponible_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->CreerMagazine($this->entityManager);
        // création adherent
        $adherent = (new \JeuDonnee())->CreerAdherent($this->entityManager);

        $this->entityManager->flush();


        // récupérer le numero adherent
        $adherent = $this->entityManager->getRepository(Adherent::class)->find($magazine->getId());
        $numeroAdherent = $adherent->getNumeroAdherent();


        // Emprunt
        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete($magazine->getId(), $numeroAdherent);
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);

        $this->expectExceptionMessage("Le media n'est pas disponible");
        $resultat = $EmprunterMedia->execute($empruntMediaRequete);
    }


    #[test]
    public function EmprunterMedia_AdherentAdhesionNonValide_Exception()
    {
        // création magazine
        $magazine = (new \JeuDonnee())->CreerMagazine($this->entityManager);

        // création adherent
        $adherent = (new \JeuDonnee())->CreerAdherent($this->entityManager);
        // rendre dipso le media

        $rendreDispoMedia = (new \JeuDonnee())->rendreDispoMedia($magazine);

        // récupérer le numero adherent et changer la date Adhesion
        $numeroAdherent = $adherent->getNumeroAdherent();
        $adherent->setDateAdhesion(new \DateTime('2021-12-18'));
        $this->entityManager->flush();

        // emprunt media
        $empruntMediaRequete = new UserStories\emprunterMedia\EmprunterMediaRequete(1, $numeroAdherent);
        $EmprunterMedia = new UserStories\emprunterMedia\EmprunterMedia($this->entityManager, $this->generateurNumeroEmprunt, $this->validateur);

        $this->expectExceptionMessage("L'adhésion de l'adhérent n'est plus valide veuillez renouvelez l'adhésion");

        $resultat = $EmprunterMedia->execute($empruntMediaRequete);
    }


}