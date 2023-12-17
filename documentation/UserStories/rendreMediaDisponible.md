# Documentation User Story : Rendre disponible un nouveau média

## User Story
**Je souhaite rendre disponible un nouveau média
Afin de le rendre empruntable par les adhérents de la bibliothèque**



#### Critères d’acceptation

1. **Média existe :**
    - Le média que l'on souhaite rendre disponible doit exister.

2. **Statut du média :**
    - Seul un média ayant le statut “Nouveau” peut être rendu disponible.


## Utilisation
### Classes impliquées
- `RendreMediaDisponible` : Classe responsable de l'exécution de la User Story.

### Classe RendreMediaDisponible
- **Entrées :**
   - **$validateur** : Validateur  `type : ValidatorInterface`
   - **entityManger** : l’entity manager paramétré pour permettre la gestion des entités avec la BDD  `type : EntityManager`

**- **Méthode execute:**
   - **RendreMediaDisponible->execeute($id)**
   - **Changement statut du media****




## Exemple d'utilisation

```php
// Instanciation de la classe RendreMediaDisponible
$rendreMediaDisponible = new RendreMediaDisponible($entityManager, $validateur);

// ID du média à rendre disponible
$mediaId = 123; // Remplacez 123 par l'ID du média que vous souhaitez rendre disponible

// Exécution de la User Story
try {
    $resultat = $rendreMediaDisponible->execute($mediaId);
    if ($resultat) {
        echo "Le média a été rendu disponible avec succès!";
    }
} catch (\Exception $e) {
    echo "Erreur lors de la tentative de rendre le média disponible : " . $e->getMessage();
}
