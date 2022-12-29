<?php
    require("lib/phpheader.php");
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Accueil</title>
    </head>
    <body>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Accueil</h1>

                    <a href="">Panneau d'administration</a><br/>
                    <a href="clients_res.php">Liste des clients résidentiels</a><br/>
                    <a href="clients_aff.php">Liste des clients d'affaire</a><br/>

                    <br/><br/>
                    <a href="auth.php">Se connecter</a><br/>
                    <a href="">Se déconnecter</a><br/>
                    <a href="changepassword.php">Changer de mot de passe</a><br/>
                </div>
            </div>
        </div>
    </body>
</html>
