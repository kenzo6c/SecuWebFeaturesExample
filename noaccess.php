<?php
    require("lib/phpheader.php");
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Erreur - Accès refusé</title>
    </head>
    <body>
        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Accès refusé</h1>
                    <p>Vous ne pouvez pas accéder à cette page.</p>
                    <a href="index.php">Retourner à la page d'accueil</a>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
