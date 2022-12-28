<?php
    require("phpheader.php");
    if (!empty($_POST["submit"]) || !$_SESSION["loggedin"])
    {
        if ($secu->isAuthValid())
        {
            if ($secu->authUser())
            {
                print($_SESSION["user"]);
                if ($_SESSION["user"] === "Administrateur")
                {
                    echo "Admin Auth OK";
                }
                elseif ($_SESSION["user"] === "Utilisateur 1")
                {
                    echo "User 1 Auth OK";
                }
                elseif ($_SESSION["user"]  === "Utilisateur 2")
                {
                    echo "User 2 Auth OK";
                }
                else
                {
                    echo "AUTH ERROR";
                    $secu->resetAccess();
                    exit();
                }
            }
            else
            {
                $secu->resetAccess();
                echo "Wrong username or password";
            }
        }
        else
        {
            $secu->resetAccess();
            echo "Invalid auth.";
        }
    }
    else
    {
        echo "Already logged in.";
        if ($_SESSION["user"] === "Administrateur")
        {
            echo "Admin Auth OK";
        }
        elseif ($_SESSION["user"] === "Utilisateur 1")
        {
            echo "User 1 Auth OK";
        }
        elseif ($_SESSION["user"]  === "Utilisateur 2")
        {
            echo "User 2 Auth OK";
        }
    }

?>
<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Document</title>
    </head>
    <body>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Page d'authentification</h1>
                    <p> Entrez votre nom d'utilisateur et votre mot de passe.</p>
                    <form action="#" method="post" name="authform">
                        <div>
                            <label for="username" class="form-label">Nom :</label>
                            <input id ="username" type="text" name="userauth[username]" value="" class="form-control" placeholder="Username" minlength=4 maxlenght=16 required>
                        </div>
                        <div>
                            <label for="password" class="form-label">Mot de passe :</label>
                            <input id ="password" type="password" name="userauth[password]" class="form-control" placeholder="Password" minlength=8 maxlength=64 required>
                        </div>
                        <div>
                            <?php $secu->insertCSRFField(); ?>
                        </div>
                        <div>
                            <br/>
                            <input type="submit" name="submit" class=" btn btn-primary" value="Se connecter">
                        </div>
                    </form>
                </diV>
            </div>
        </div>
    </body>
</html>