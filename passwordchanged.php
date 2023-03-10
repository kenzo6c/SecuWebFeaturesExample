
<?php
    require("lib/phpheader.php");
    if (!$_SESSION["passwordchanged"])
    {
        header("Location: index.php");
        exit();
    }
    $_SESSION["passwordchanged"] = false;
?>

<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Mot de passe modifié</title>
    </head>
    <body>

        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Mot de passe modifié</h1>
                    <p>Votre mot de passe a été modifié.</p>
                    <a href="index.php">Retourner à la page d'accueil</a>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
