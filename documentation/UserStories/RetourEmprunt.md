# User story "Retour un Emprunt"

**En tant que bibliothécaire
je veux enregistrer le retour d'un média à partir du numéro d'emprunt
Afin de rendre le média disponible pour un prochain emprunt**

## Critères d'acceptation

## Emprunt

- le numero d'emprunt doit exister
- l'emprunt ne doit pas avoir été restitué
- la date de retour doit être mise à jour avec la date du jour

## Media

- le média doit être positionné à l'état disponible dès que l'emprunt est retourné

---

# Processus Rendre Emprunt
## Entrés Utilisateurs
- **Numero Emprunt :** le bibliothécaire sera invité à fournir le numero d'emprunt pour retourner un emprunt

## Fonctionnement

1. **Validation des Entrées :** Les entrées du bibliothécaire sont validées à l'aide du service de validation.

2. **Exécution de Retour emprunt :** La commande utilise la classe R pour exécuter l'emprunt en fonction des
   règles définies ci-dessous.

3. **Message :** En fonction du résultat de l'emprunt, un message de succès ou d'erreur est affiché à l'utilisateur.


## Tests
````batch
./vendor/bin/phpunit --testsuite Integration --testdox --colors=always tests/Integrations/UserStories/RetourEmpruntTest.php
````

## Commande silly
````batch
php app.php biblio:retour:Emprunt
````


