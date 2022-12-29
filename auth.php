<?php
    require("lib/phpheader.php");

    if (!empty($_POST["submit"]))
    {
        print($_POST["submit"]);
        if ($secu->isFormValid("userauth", ["username", "password"]))
        {
            if ($secu->hasAttempts($_POST["userauth"]["username"]))
            {
                $waitingTimeLeft = $secu->waitingTimeLeft();
                if ($waitingTimeLeft > 0)
                {
                    $secu->disconnect();
                    echo "Please wait " . $waitingTimeLeft . " seconds.";
                }
                else
                {
                    if ($secu->authUser($_POST["userauth"]))
                    {
                        print($_SESSION["user"]);
                    }
                    else
                    {
                        $secu->disconnect();
                        $secu->decrementAttempts($_POST["userauth"]["username"]);
                        echo "Wrong username or password";
                    }
                }
            }
            else
            {
                $secu->disconnect();
                echo "No attempts left, the account is locked, please contact an administrator for a password reset.";
            }
        }
        else
        {
            $secu->disconnect();
            echo "Invalid auth.";
        }
    }
    elseif ($_SESSION["loggedin"])
    {
        echo "Already logged in.";
    }

?>
<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Authentification</title>
    </head>
    <body>

        <?php
            require_once("navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Page d'authentification</h1>
                    <p>Entrez votre nom d'utilisateur et votre mot de passe.</p>
                    <form action="#" method="post" name="authform">
                        <div>
                            <label for="username" class="form-label">Nom d'utilisateur :</label>
                            <input id ="username" type="text" name="userauth[username]" value="" class="form-control" placeholder="Nom d'utilisateur" required>
                        </div>
                        <div>
                            <label for="password" class="form-label">Mot de passe :</label>
                            <input id ="password" type="password" name="userauth[password]" class="form-control" placeholder="Mot de passe" required>
                        </div>
                        <div>
                            <?php $secu->insertCSRFField(); ?>
                        </div>
                        <div>
                            <br/>
                            <input type="submit" name="submit" class=" btn btn-primary" value="Se connecter">
                        </div>
                        <div>
                            <br/>
                            <a href="changepassword.php" class="btn btn-primary" value="Se connecter">Changer de mot de passe</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>