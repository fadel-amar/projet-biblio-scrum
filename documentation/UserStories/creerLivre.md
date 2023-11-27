# Documentation de la User Story - Créer un Livre

## Description

La User Story "Créer un Livre" est le processus de création d'un nouveau livre dans le système de gestion de
bibliothèque et de l'enregistrer dans la BDD.

## Classes impliquées

- `CreerLivre` : Classe responsable de l'exécution de la User Story.
- `Livre` : Classe représentant l'entité Livre avec ses propriétés.
- `CreerLivreRequete` : Classe représentant la requête pour la création d'un livre avec ses propriétés.

## Création d'un Livre

### Classe CreerLivreRequete

- **Entrées :**
    - **$isbn**: Identifiant unique du livre `type: string`
    - **$auteur** Identifiant unique du livre `type: string`
    - **$titre** : Titre du livre  `type: string`
    - **$dateCreation** : Date création du media Livre `type: string`
    - **$nbPages**: Le nombre de pages du livre `type: int`


### Classe CreerLivre

- **Entrées :**
  - **$validateur** : Validateur de Symfony avec les annotations actives  `type : ValidatorInterface`
  - **entityManger** : l’entity manager paramétré pour permettre la gestion des entités avec la BDD  `type : EntityManager`

- **$creerLivre->execute($requete): bool**
    - Type `$requete` : `CreerLivreRequete`
    - Enregistrement du Livre dans la base de données sinon elle renvoie false.

## Exemple d'utilisation

```php
// Instanciation de la classe CreerLivre
$creerLivre = new CreerLivre($entityManager, $validateur);

// Création de la requête pour la création du livre
$requete = new CreerLivreRequete("123456789", "Victor Hugo", "Les Misérables", "01/01/1862", 120);

// Exécution de la User Story
try {
    $resultat = $creerLivre->execute($requete);
    if ($resultat) {
        echo "Le livre a été créé avec succès!";
    }
} catch (Exception $e) {
    echo "Erreur lors de la création du livre : " . $e->getMessage();
}
