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
            require_once("lib/navbar.php")
        ?>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Accueil</h1>

                    <p>
                        <?php
                            $no_access = true;
                            if ($secu->hasAccess("root"))
                            {
                                echo "<a href=\"admin.php\">Panneau d'administration</a><br/>";
                                $no_access = false;
                            }
                            if ($secu->hasAccess("clients_res"))
                            {
                                echo "<a href=\"clients_res.php\">Liste des clients résidentiels</a><br/>";
                                $no_access = false;
                            }
                            if ($secu->hasAccess("clients_aff"))
                            {
                                echo "<a href=\"clients_aff.php\">Liste des clients d'affaire</a><br/>";
                                $no_access = false;
                            }

                            if ($no_access)
                            {
                                echo "<a href=\"auth.php\">Se connecter</a><br/>";
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
