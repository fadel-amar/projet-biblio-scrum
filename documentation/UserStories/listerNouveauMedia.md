# Documentation User Story "Lister Nouveaux Medias"

## User story

**En tant que bibliothécaire
Je veux lister les nouveaux médias
Afin de les rendre disponibles aux adhérents de la bibliothèque**

## Description

Lister tous les médias dont le status est à Nouveau dans l'ordre décroissant *

# Critères d'acceptation

## Valeurs retournées

Pour chaque média présent dans la liste, les informations suivantes devront être retournées :

- L'ID du média
- Le titre du média
- Le statut du média
- La date de création du média (date à laquelle il a été créé dans la base de données)
- Le type du média (livre, bluray ou magazine)

## Ordre de tri

La liste devra être triée par date de création décroissante.

---

# Processus Lister Nouveaux Medias

## Fonctionnement

**Exécution de Lister Nouveaux Medias:** La commande utilise la classe ListerNouveauxMedias pour renvoyer un tableau des
nouveaux medias.


## Utilisation

### Classe impliquée

- `ListerNouveauxMedias` : Classe responsable de l'exécution de la User Story.
- `sortie` : Tableau Objets Medias

## Tests
````batch
./vendor/bin/phpunit --testsuite Integration --testdox --colors=always tests/Integrations/UserStories/ListerNouveauxMediasTest.php
````

## Commande silly 
````batch
php app.php biblio:listMedia:Nouveau 
````


## Exemple d'utilisation

```php
$medias =(ListerNouveauxMedias($entityManager))->execute();
    $mediaNoObjet = [];
    foreach ($medias as $media) {
        $mediaNoObjet[] = [
                $media->getId(),
                 $media->getTitre(), $media->getStatus(),
                  $media->getDateCreation(),
                   (new ReflectionClass($media))->getShortName()
        ];
    }




