<?php
require "./vendor/autoload.php";
require "./bootstrap.php";

use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\creerBluRay\CreerBluRay;
use App\UserStories\creerBluRay\CreerBluRayRequete;
use App\UserStories\creerLivre\CreerLivre;
use App\UserStories\creerLivre\creerLivreRequete;
use App\UserStories\creerMagazine\CreerMagazineRequete;
use App\UserStories\emprunterMedia\EmprunterMedia;
use App\UserStories\emprunterMedia\EmprunterMediaRequete;
use App\UserStories\rendreMediaDisponible\RendreMediaDisponible;
use App\UserStories\retourEmprunt\RetourEmprunt;
use App\UserStories\retourEmprunt\RetourEmpruntRequete;
use Silly\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ValidatorBuilder;


// Définir les commandes
$app = new Application();

$app->command('biblio:add:Livre', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
    $titre = $io->ask("Le titre du livre: ");
    $isbn = $io->ask("L'isbn du livre: ");
    $auteur = $io->ask("L'auteur du livre: ");
    $nbPages = $io->ask("Le nombre de Pages du livres: ");

    $creerLivre = new CreerLivre($entityManager, $validateur);
    $requete = new creerLivreRequete($isbn, $auteur, $titre, $nbPages);

    try {
        $resultat = $creerLivre->execute($requete);
        if ($resultat) {
            $io->success("Le livre a été créé avec succès!");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de la création du livre : \n" . $e->getMessage());
    }

});


$app->command('biblio:add:Magazine', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
    $numero = $io->ask("Le numéro du magazine: ");
    $titre = $io->ask("Le titre du magazine: ");
    $datePublication = $io->ask("La date de publication du magazine ");

    $creerMagazine = new \App\UserStories\creerMagazine\CreerMagazine($entityManager, $validateur);
    $requete = new CreerMagazineRequete($numero, $titre, $datePublication);

    try {
        $resultat = $creerMagazine->execute($requete);
        if ($resultat) {
            $io->success("Le magazine a été créé avec succès!");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de la création du magazine: \n " . $e->getMessage());
    }
});

$app->command('biblio:add:BluRay', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
    $realisateur = $io->ask("Le réalisateur du Blu-Ray: ");
    $titre = $io->ask("Le titre du Blu-Ray: ");
    $datePublication = $io->ask("L'année de publication du Blu-Ray: ");
    $duree = $io->ask("La durée du Blu-Ray: ");

    $creerBluRay = new CreerBluRay($entityManager, $validateur);
    $requete = new CreerBluRayRequete($titre, $realisateur, $datePublication, $duree);

    try {
        $resultat = $creerBluRay->execute($requete);
        if ($resultat) {
            $io->success("Le Blu-Ray est créé avec succès!");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de la création du Blu-Ray: \n " . $e->getMessage());
    }
});


$app->command('biblio:listMedia:Nouveau', function (SymfonyStyle $io) use ($entityManager) {
    $medias = (new \App\UserStories\listerNouveauxMedias\ListerNouveauxMedias($entityManager))->execute();
    $io->table(['id', 'titre', 'statut', 'dateCreation', 'typeMedia'], $medias);
});


$app->command('biblio:setStatutMedia:Disponible', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();

    $id = $io->ask("L'id du media que vous voulez rendre disponible");
    if (!$id) {
        $io->error("L'id du media est obligatoire");
        return false;
    }
    try {
        $resultat = (new RendreMediaDisponible(
            $entityManager, $validateur
        ))->execute($id);
        if ($resultat) {
            $io->success("Le media a était rendu disponible");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors du changement du statut Media: \n " . $e->getMessage());
    }
});


$app->command('biblio:add:Emprunt', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();

    $id = $io->ask("L'id du media que vous voulez empruntez");
    $Noadherent = $io->ask("Le numero d'adherent  qui veut emprunter le media");
    $generateurNumeroEmprunt = new GenerateurNumeroEmprunt($entityManager);
    $emprunterMedia = new EmprunterMedia($entityManager, $generateurNumeroEmprunt, $validateur);
    $emprunterMediaRequete = (new EmprunterMediaRequete($id, $Noadherent));

    try {
        $resultat = $emprunterMedia->execute($emprunterMediaRequete);

        if ($resultat) {
            $io->success("Le media a bien été emprunté");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de l'emprunt du media : \n " . $e->getMessage());
    }
});


$app->command('biblio:retour:Emprunt', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();

    $numeroEmprunt = $io->ask("Le numero d'emprunt que vous vous voulez retourner");

    $requete = new RetourEmpruntRequete($numeroEmprunt);
    $rendreEmprunt = new RetourEmprunt($entityManager, $validateur);

    try {
        $resultat = $rendreEmprunt->excute($requete);

        if ($resultat) {
            $io->success("L'emprunt à bien été rendu");
        }
    } catch (Exception $e) {
        $io->error("Erreur l'emprunt n'a pas pu être rendu \n " . $e->getMessage());
    }
});

$app->run();
