# Documentation de la User Story - Lister Nouveaux Medias

## User story
**En tant que bibliothécaire
Je veux lister les nouveaux médias
Afin de les rendre disponibles aux adhérents de la bibliothèque**


## Description 
Lister tous les médias dont le status est à Nouveau dans l'ordre décroissant 

## Utilisation
## Classe impliquée
- `ListerNouveauxMedias` : Classe responsable de l'exécution de la User Story.
- `sortie` : Tableau Objets Medias


### Classe ListerNouveauxMedias
- **Entrées :**
    - **$validateur** : Validateur   `type : ValidatorInterface`
    - **entityManger** : l’entity manager paramétré pour permettre la gestion des entités avec la BDD  `type : EntityManager`
    - 
- **Méthode execute:**
    - **ListerNouveauxMedias->execeute()**
    - **list objet Media ayant le statut Nouveau**


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




