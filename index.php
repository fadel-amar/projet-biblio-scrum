<?php
require_once "./bootstrap.php";

use App\Services\GenerateurNumeroAdherent;
use App\UserStories\creerAdherant\CreerAdherent;
use  App\UserStories\creerAdherant\CreerAdherentRequete;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ValidatorBuilder;

$prenom = null;
$nom = null;
$email = null;

$formulaire = true;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $prenom = $_POST["prenom"];
    $nom = $_POST["nom"];
    $email = $_POST["email"];

    $generateurNumeroAdherent = new \App\Services\GenerateurNumeroAdherent();
    $validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerAdherent = new CreerAdherent($entityManager, $generateurNumeroAdherent, $validateur);
    $requete = new CreerAdherentRequete($_POST["prenom"], $_POST["nom"], $_POST["email"]);

    // Execution de la requête
    try {
        $executed = $creerAdherent->execute($requete);
        if (!$executed) {
            throw new Exception("La création d'un nouvel adhérant n'a pas pu se faire!");
        } else {
            $afficheFormulaire = false;
        }


    } catch (Exception $e) {
        $message = $e->getMessage();
    }


}

?>

<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .erreur-validation {
        color: red;
    }

    form {
        text-align: center;
        background: #a5a5cb;
        padding: 50px;
        border-radius: 10px;


    }

    form * {
        display: block;
        margin: 10px auto;

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

<?php if ($formulaire) { ?>

    <form action="" method="post">
        <h1>Inscription Adherent </h1>

        <label for="prenom">Prenom*</label>
        <input type="text" id="prenom" name="prenom" value="<?= $prenom ?>">


        <label for="nom">Nom*</label>
        <input type="text" id="nom" name="nom" value="<?= $nom ?>">




        <label for="email">Email*</label>
        <input type="text" id="email" name="email" value="<?= $email ?>">

        <?php if (isset($message)) echo "<label class=\"erreur-validation\">$message</label>" ?>


        <p>* : Champs obligatoires</p>

        <input type="submit" value="Envoyer">


    </form>


<?php }?>


</body>
</html>