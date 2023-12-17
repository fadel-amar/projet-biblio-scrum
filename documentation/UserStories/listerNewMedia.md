# Documentation de la User Story - Lister Nouveaux Medias

## User story
**En tant que bibliothécaire
Je veux lister les nouveaux médias
Afin de les rendre disponibles aux adhérents de la bibliothèque**


## Description 
Lister tous les médias dont le status est à Nouveau dans l'ordre décroissant 

## Classe impliquée
- `ListerNouveauxMedias` : Classe responsable de l'exécution de la User Story.
- `sortie` : Tableau Objets Medias

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




