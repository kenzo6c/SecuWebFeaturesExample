<?php
    require("phpheader.php");
    if ($_SESSION["loggedin"])
    {
        if (!empty($_POST["submit"]))
        {
            if ($secu->isFormValid("chgepass", ["old", "new"]))
            {
                if ($secu->verifyPassword($_SESSION["user"], $_POST["chgepass"]["old"]))
                {
                    echo "Password changed.";
                    $secu->changePassword($_SESSION["user"], $_POST["chgepass"]["new"], $config["hashAlgorithm"]);
                    echo "Ancien mdp :" . $_POST["chgepass"]["old"] . "<br/>";
                    echo "Nouveau mdp :" . $_POST["chgepass"]["new"] . "<br/>";
                    echo "hash du nouveau mdp :" . password_hash($_SESSION["user"], PASSWORD_ARGON2ID) . "<br/>";
                }
                else
                {
                    echo "Wrong password.";
                }
            }
            else
            {
                echo "Invalid auth.";
            }
        }
    }
    else
    {
        echo "You are not logged in.";
        // header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Changement mot de passe</title>
    </head>
    <body>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Changement de mot de passe</h1>
                    <p> Entrez votre ancien mot de passe et celui par lequel vous souhaitez le remplacer.</p>
                    <form action="#" method="post" name="authform">
                        <div>
                            <label for="username" class="form-label">Ancien mot de passe :</label>
                            <input id ="username" type="text" name="chgepass[old]" value="" class="form-control" placeholder="Ancien mot de passe" minlength=4 maxlenght=16 required>
                        </div>
                        <div>
                            <label for="password" class="form-label">Nouveau mot de passe :</label>
                            <input id ="password" type="password" name="chgepass[new]" class="form-control" placeholder="Nouveau mot de passe" minlength=8 maxlength=64 required>
                        </div>
                        <div>
                            <?php $secu->insertCSRFField(); ?>
                        </div>
                        <div>
                            <br/>
                            <input type="submit" name="submit" class=" btn btn-primary" value="Changer le mot de passe">
                        </div>
                    </form>
                </diV>
            </div>
        </div>
    </body>
</html>