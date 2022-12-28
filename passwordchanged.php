
<?php
    require("lib/phpheader.php");
    if (!$_SESSION["passwordchanged"])
    {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title> Mot de passe modifié </title>
    </head>
    <body>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Mot de passe modifié</h1>
                    <p> Le mot de passe a été modifié. </p>
                    <a href="index.php">Retourner à la page d'accueil</a>
                </diV>
            </div>
        </div>
    </body>
</html>
