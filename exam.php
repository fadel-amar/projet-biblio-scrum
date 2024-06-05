<?php

// 1. Créer la connexion à la db_biblio
const DB_HOST = "localhost:3306";
const DB_NAME = "db_biblio";
const DB_USER = "root";
const DB_PASSWORD = "";

function createConnection(): PDO
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset = utf8";
    try {
        $connexion = new PDO ($dsn, DB_USER, DB_PASSWORD);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexion;
    } catch (PDOException $erreur) {
        die("Erreur : " . $erreur->getMessage());
    }
}

$annee = readline("Entrez une année: ");

function selectLivresByAnnee( int $annee): array {

    $connexion = createConnection();
    $requetSQL = "select media.titre, livre.isbn,  COUNT(*) as nb_emprunts
                from emprunt 
                inner join media on media.id = emprunt.id_media
                inner join livre ON media.id = livre.id
                where YEAR(emprunt.dateEmprunt) = :annee
                GROUP BY Media.titre,  livre.isbn
                HAVING COUNT(DISTINCT emprunt.id_adherent) >= 3";


    $requete = $connexion->prepare($requetSQL);
    $requete->bindValue(":annee", $annee);

    $requete->execute();
    $livres = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $livres;
}

$livres = selectLivresByAnnee($annee);

foreach($livres as  $livre) {
    echo "-------------------\n";
    echo "titre : $livre[titre]\n";
    echo "isbn : $livre[isbn]\n";
    echo "nb_emprunts : $livre[nb_emprunts]\n";
    echo "-------------------\n";
}


