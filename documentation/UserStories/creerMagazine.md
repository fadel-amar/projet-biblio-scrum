# Documentation de la User Story - Créer un Magazine

## User story
**En tant que bibliothécaire
Je veux créer un magazine
Afin de le rendre accessible aux adhérents de la bibliothèque**

La User Story "Créer un Magazine" est le processus de création d'un nouveau magazine dans le système de gestion de
bibliothèque et de l'enregistrer dans la BDD.





## Classes impliquées

- `CreerMagazine` : Classe responsable de l'exécution de la User Story.
- `Magazine` : Classe représentant l'entité Magazine avec ses propriétés.
- `CreerMagazineRequete` : Classe représentant la requête pour la création d'un magazine avec ses propriétés.

## Création d'un Magazine

### Classe CreerMagazineRequete

- **Entrées :**
  - **$numero** : Nuemro du magazine  `type: string`
  - **$titre** : Titre du Magazine `type: string`
  - **$datePublication**: La date de publication du magazine `type: string`
  - **$dateCreation**: La date de création du magazine `type: string`


### Classe CreerMagazine

- **Entrées :**
  - **$validateur** : Validateur de Symfony avec les annotations actives  `type : ValidatorInterface`
  - **entityManger** : l’entity manager paramétré pour permettre la gestion des entités avec la BDD  `type : EntityManager`

- **$creerMagazine->execute($requete): bool**
  - Type `$requete` : `CreerMagazineRequete`
  - Enregistrement du Magazine dans la base de données sinon elle renvoie false.

## Exemple d'utilisation

```php
// Instanciation de la classe CreerMagazine
$creerMagazine = new CreerMagazine($entityManager, $validateur);

// Création de la requête pour la création du magazine

$requete = new CreerMagazineRequete("545345", "CNEWS", nch "01/11/2023", "31/10/2023");

// Exécution de la User Story
try {
    $resultat = $creerMagazine->execute($requete);
    if ($resultat) {
        echo "Le magazine a été créé avec succès!";
    }
} catch (Exception $e) {
    echo "Erreur lors de la création du magazine : " . $e->getMessage();
}
