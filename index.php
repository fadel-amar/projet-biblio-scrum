<?php

$nom = null;
$prenom = null;
$email = null;
$dateAdhesion = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Tester le nom
    if (empty(trim($_POST["nom"]))) {
        $erreurs["nom"] = "Le nom est obligatoire";
    } else {
        $nom = $_POST["nom"];
    }

    //Tester le prenom
    if (empty(trim($_POST["prenom"]))) {
        $erreurs["prenom"] = "Le prenom est obligatoire";
    } else {
        $nom = $_POST["prenom"];
    }


    // Tester le mail
    if (empty(trim($_POST["email"]))) {
        $erreurs["email"] = "L'email est obligatoire";
    } else {
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email = $_POST["email"];
        } else {
            $erreurs["email"] = "L'email n'est pas valide";
        }
    }


    if (empty($erreurs)) {
        $generateurNumeroAdherent = new \App\Services\GenerateurNumeroAdherant();
        $requete = new \App\UserStories\CreerAdherantRequete("$prenom","$nom", "$email");
        $createAdherent = new \App\UserStories\CreerAdherent\CreerAdherant();

    /*    try {
            $validateur->verifierNombre(-110);
            echo "le nombre est positif";
        } catch (\App\Exceptions\NombreException $e) {
            echo $e->getMessage();
        }*/

    }

}

?>

<style>
    .erreur-validation {
        color : red;
    }
</style>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Creer Adhérent</title>
</head>
<body>


<form action="" method="post">

    <label for="nom">Nom*</label>
    <input type="text" id="nom" name="nom" value="<?= $nom ?>">
    <?php // tester si il y'a une erreur
    if (isset($erreurs["nom"])) { ?>
        <p class="erreur-validation"><?= $erreurs["nom"] ?> </p>
    <?php } ?>


    <label for="prenom">Prenom*</label>
    <input type="text" id="prenom" name="prenom" value="<?= $prenom ?>">
    <?php // tester si il y'a une erreur
    if (isset($erreurs["prenom"])) { ?>
        <p class="erreur-validation"><?= $erreurs["prenom"] ?> </p>
    <?php } ?>

    <label for="email">Email*</label>
    <input type="text" id="email" name="email" value="<?= $email ?>">
    <?php // tester si il y'a une erreur
    if (isset($erreurs["email"])) { ?>
        <p class="erreur-validation"><?= $erreurs["email"] ?> </p>
    <?php } ?>



    <p>* : Champs obligatoires</p>

    <input type="submit" value="Envoyer">


</form>


</body>
</html>