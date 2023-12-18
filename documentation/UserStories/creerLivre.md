# Documentation de la User Story "Créer un Livre"

## User story
**En tant que bibliothécaire
Je veux créer un livre
Afin de le rendre accessible aux adhérents de la bibliothèque**

Définition : Description simple d’un besoin ou d’une attente exprimée par un utilisateur

La User Story "Créer un Livre" est le processus de création d'un nouveau livre dans le système de gestion de
bibliothèque et de l'enregistrer dans la BDD.




#### Critères d’acceptation

* Validation des données
  * Le titre, l’ISBN, l’auteur, le nombre de pages et la date de parution doivent être renseignés.
  * L’ISBN doit être unique.
* Enregistrement dans la base de données
  * Les informations du livre doivent être correctement enregistrées dans la base de données.
* Messages d’erreurs
  * Des messages d’erreur explicites doivent être retournés en cas d’informations manquantes ou incorrectes.
* Cas du statut et de la durée de l’emprunt
  * Le statut par défaut lors de la création d’un livre devra être ‘Nouveau’.
  * La durée de l’emprunt devra être égale à la durée définie lors de la présentation du projet.

#### Informations
* Statut
  * Concernant le statut d’un livre :
    * A la création il sera mis à ‘Nouveau’ : cela signifie que le livre ne sera pas encore disponible auprès des adhérents. Les informations saisies concernant le livre devront faire l’objet d’une vérification;
    * A la vérification des informations (cela fera l’objet d’une autre UserStory), et si elles sont validées, le statut passera à ‘Disponible’;
    * A l’emprunt par un adhérent (une autre UserStory), le statut du livre passera à ‘Emprunte’;
    * Au retrait du livre pour une raison particulière (une autre UserStory), le statut passera à ‘Non disponible’.



## Utilisation
### Classes impliquées

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
