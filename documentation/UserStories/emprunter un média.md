# Documentation User Story "Emprunter un média"

## User story

**En tant que bibliothécaire, je souhaite enregistrer un emprunt de média disponible pour un adhérent, afin de permettre
à l’adhérent d’utiliser ce média pour une durée déterminée.**

## Indications

Pour enregistrer l’emprunt, le bibliothécaire aura besoin de l’id du média et du numéro d’adhérent de l’emprunteur.

## Critères d'acceptation

### Média

- Le média doit exister dans la base de données.
- Le média doit être disponible.

### Adhérent

- L’adhérent doit exister dans la base de données.
- L’adhésion de l’adhérent doit être valide.

### Enregistrement dans la base de données

- L’emprunt doit être correctement enregistré dans la base de données.
- La date de retour prévue doit être correctement initialisée en fonction du média emprunté (livre, bluray ou magazine)
  ainsi que la date d’emprunt.

### À l’issue de l’enregistrement de l’emprunt

- Le statut du média doit être à “Emprunté”.

---

# Processus d'Emprunt de Média

## Entrées Utilisateur

- **ID du Média:** L'utilisateur sera invité à fournir l'ID du média qu'il souhaite emprunter.
- **Numéro d'Adhérent:** L'utilisateur devra saisir le numéro d'adhérent de la personne qui souhaite emprunter le média.

## Fonctionnement

1. **Validation des Entrées:** Les entrées de l'utilisateur sont validées à l'aide du service de validation.
2. **Génération du Numéro d'Emprunt:** Avant l'enregistrement, un numéro d'emprunt est généré de manière incrémentale et
   stocké dans la base de données.
3. **Exécution de l'Emprunt:** La commande utilise la classe EmprunterMedia pour exécuter l'emprunt en fonction des
   règles définies ci-dessous.
4. **Message :** En fonction du résultat de l'emprunt, un message de succès ou d'erreur est affiché à l'
   utilisateur.


## Tests
````batch
./vendor/bin/phpunit --testsuite Integration --testdox --colors=always tests/Integrations/UserStories/EmprunterMediaTest.php
````

## Commande silly
````batch
php app.php biblio:add:Emprunt
````

## Exemple Utilisation
```php

// Création d'un générateur de numéro d'emprunt
$generateurNumeroEmprunt = new \App\Services\GenerateurNumeroEmprunt($entityManager);

// Création d'une instance de la classe EmprunterMedia
$emprunterMedia = new \App\UserStories\emprunterMedia\EmprunterMedia($entityManager, $generateurNumeroEmprunt, $validateur);

$requete = new \App\UserStories\emprunterMedia\EmprunterMediaRequete($idMedia, $numeroAdherent);

try {
    // Exécution de l'emprunt
    $resultat = $emprunterMedia->execute($requete);

    if ($resultat) {
        echo "Le média a bien été emprunté.\n";
    }
} catch (\Exception $e) {
    echo "Erreur lors de l'emprunt du média : \n " . $e->getMessage() . "\n";
}


````

*Cette documentation sert de guide pour implémenter et tester la fonctionnalité "Emprunter un média" dans
l'application.*


