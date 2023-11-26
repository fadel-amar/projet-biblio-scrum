# Documentation de la User Story - Créer un Livre

## Description

La User Story "Créer un Livre" décrit le processus de création d'un nouveau livre dans le système de gestion de
bibliothèque.


### 1. Création d'un Livre

- **Objectif :** Permet de créer un nouveau livre avec les informations nécessaires.

- **Entrées :**
    - ISBN du livre `string`
    - Auteur du livre `string`
    - Titre du livre `string`
    - Nombre de pages `int`
    - Date de création `string`

-
- **Validation :**
  - Type : `ValidatorInterface`
  - Vérification des données fournies pour s'assurer qu'elles sont valides.

- **execute($requete): bool:**
  - Type `$requete` : `CreerLivreRequete`
  - Enregistrement du Livre dans la base de données sinon elle renvoie false.



## Classes impliquées

- `CreerLivre` : Classe responsable de l'exécution de la User Story.
- `Livre` : Classe représentant l'entité Livre avec ses propriétés.
- `CreerLivreRequete` : Classe représentant la requête pour la création d'un livre avec ses propriétés.

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
