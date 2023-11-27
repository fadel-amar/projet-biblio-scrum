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

$app->command('biblio:livre:add [name]', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $titre = $io->ask("Le titre du livre: ");
    $isbn = $io->ask("L'isbn du livre: ");
    $auteur = $io->ask("Lauteur du livre: ");
    $dateCreation = $io->ask("Date création du livre: ");
    $nbPages = $io->ask("Le nombre de Pages du livres: ");

    $creerLivre = new CreerLivre($entityManager,$validateur);
    $requete = new creerLivreRequete($isbn, $auteur,$titre, $dateCreation,$nbPages);

    try {
        $resultat = $creerLivre->execute($requete);
        if ($resultat) {
            $io->success( "Le livre a été créé avec succès!");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de la création du livre : " . $e->getMessage());
    }

});






$app->command('biblio:magazine:add [name]', function (SymfonyStyle $io) use ($entityManager) {
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $numero = $io->ask("Le numéro du magazine: ");
    $titre = $io->ask("Le titre du magazine: ");
    $datePublication = $io->ask("La date de publication du magazine ");
    $dateCreation = $io->ask("La date de création du magazine: ");


    $creerMagazine = new \App\UserStories\creerMagazine\CreerMagazine($entityManager,$validateur);
    $requete = new CreerMagazineRequete($numero,$titre ,$datePublication, $dateCreation);

    try {
        $resultat = $creerMagazine->execute($requete);
        if ($resultat) {
            $io->success( "Le magazine a été créé avec succès!");
        }
    } catch (Exception $e) {
        $io->error("Erreur lors de la création du magazine: " . $e->getMessage());
    }
});



$app->run();
