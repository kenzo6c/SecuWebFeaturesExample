<?php
    require("lib/phpheader.php");
    if ($_SESSION["loggedin"])
    {
        if (!empty($_POST["submit"]))
        {
            if ($secu->isFormValid("chgepass", ["old", "new"]))
            {
                if ($secu->verifyPassword($_SESSION["user"], $_POST["chgepass"]["old"]))
                {
                    $secu->changePassword($_SESSION["user"], $_POST["chgepass"]["new"], $config["hashAlgorithm"]);
                    $secu->disconnect();
                    $_SESSION["passwordchanged"] = true;
                    header("Location: passwordchanged.php");
                    exit();
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
        require("noaccess.php");
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

        <?php
            require_once("navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Changement de mot de passe</h1>
                    <p>Entrez votre ancien mot de passe et celui par lequel vous souhaitez le remplacer.</p>
                    <form action="#" method="post" name="authform">
                        <div>
                            <label for="oldpasswrd" class="form-label">Ancien mot de passe :</label>
                            <input id ="oldpasswrd" type="password" name="chgepass[old]" class="form-control" placeholder="Ancien mot de passe" minlength=8 maxlenght=64 required>
                        </div>
                        <div>
                            <label for="newpassword" class="form-label">Nouveau mot de passe :</label>
                            <input id ="newpassword" type="password" name="chgepass[new]" class="form-control" placeholder="Nouveau mot de passe" minlength=8 maxlength=64 required>
                        </div>
                        <div>
                            <?php $secu->insertCSRFField(); ?>
                        </div>
                        <div>
                            <br/>
                            <input type="submit" name="submit" class=" btn btn-primary" value="Changer le mot de passe">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>