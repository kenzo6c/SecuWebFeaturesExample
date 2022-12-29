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
        <?php
            require_once("navbar.php")
        ?>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Accueil</h1>

                    <p>
                        <a href="admin.php">Panneau d'administration</a><br/>
                        <a href="clients_res.php">Liste des clients résidentiels</a><br/>
                        <a href="clients_aff.php">Liste des clients d'affaire</a><br/>
                    </p>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
