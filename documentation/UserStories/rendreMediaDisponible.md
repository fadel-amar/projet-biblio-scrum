# Documentation User Story "Rendre disponible un nouveau média"

## User Story

**Je souhaite rendre disponible un nouveau média
Afin de le rendre empruntable par les adhérents de la bibliothèque**

#### Critères d’acceptation

*Média existe :*
Le média que l'on souhaite rendre disponible doit exister.

*Statut du média :*
Seul un média ayant le statut “Nouveau” peut être rendu disponible.


---
# Processus d'Emprunt de Média

## Entrées Utilisateur

- **ID du Média:** L'utilisateur sera invité à fournir l'ID du média qu'il souhaite rendre disponible.

## Fonctionnement

- **Entrées :**
    - **Validation des Entrées:** Les entrées de l'utilisateur sont validées à l'aide du service de validation.
    - **Exécution RendreMediaDisponible:** La commande utilise la classe RendreMediaDisponible pour rendre le média
      disponible.
    - **Message :** En fonction du résultat de la commande, un message de succès ou d'erreur est affiché à l'
      utilisateur.


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
