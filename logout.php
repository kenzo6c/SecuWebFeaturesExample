
<?php
    require("lib/phpheader.php");

    $secu->disconnect();
?>

<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Déconnecté</title>
    </head>
    <body>

        <?php
            require_once("navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Déconnexion</h1>
                    <p>Vous avez été déconnecté.</p>
                    <a href="index.php">Retourner à la page d'accueil</a>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
