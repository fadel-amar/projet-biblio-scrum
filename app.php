<?php
require "./vendor/autoload.php";
require "./bootstrap.php";

use App\UserStories\creerLivre\CreerLivre;
use App\UserStories\creerLivre\creerLivreRequete;
use App\UserStories\creerMagazine\CreerMagazineRequete;
use Silly\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ValidatorBuilder;


// Définir les commandes
$app = new Application();

$app->command('biblio:add:Livre [name]', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
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


$app->command('biblio:add:Magazine [name]', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
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


$app->command('biblio:listMedia:Nouveau [name]', function (SymfonyStyle $io) use ($entityManager) {
    $medias =( new \App\UserStories\listerNouveauxMedias\ListerNouveauxMedias($entityManager))->execute();
    $mediaNoObjet = [];
    foreach ($medias as $media) {

        $mediaNoObjet[] = [$media->getId(), $media->getTitre(), $media->getStatus(), $media->getDateCreation()->format('d/m/Y'), (new ReflectionClass($media))->getShortName() ];
    }

    $io->table(['id', 'titre', 'statut', 'dateCreation', 'typeMedia'],$mediaNoObjet);
});


$app->command('biblio:setStatutMedia:Disponible:Media [name]', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

    $id = $io->ask("L'id du media que vous voulez rendre disponible");
    if(!$id) {
        $io->error("L'id du media est obligatoire");
        return false;
    }
    try {
        $resultat =( new \App\UserStories\rendreMediaDisponible\RendreMediaDisponible(
            $entityManager, $validateur
        ))->execute($id);
        if ($resultat) {
            $io->success("Le media a était rendu disponible");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors du changement du statut Media: \n " . $e->getMessage());
    }
});


$app->command('biblio:add:Emprunt [name]', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

    $id = $io->ask("L'id du media que vous voulez empruntez");
    $Noadherent = $io->ask("Le numero d'adherent  qui veut emprunter le media");
    $generateurNumeroEmprunt = new \App\Services\GenerateurNumeroEmprunt($entityManager);
    $emprunterMedia = new \App\UserStories\emprunterMedia\EmprunterMedia($entityManager, $generateurNumeroEmprunt, $validateur );
    $emprunterMediaRequete =( new \App\UserStories\emprunterMedia\EmprunterMediaRequete($id,$Noadherent));

    try {
        $resultat =$emprunterMedia->execute($emprunterMediaRequete);

        if ($resultat) {
            $io->success("Le media a bien été emprunté");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de l'emprunt du media : \n " . $e->getMessage());
    }
});

$app->run();
