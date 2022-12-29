<?php
    require("lib/phpheader.php");

    function auth()
    {
        # --- Global variables ---
        global $secu;
        global $config;

        # --- Guard Clauses & Functional Code ---
        if (!empty($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
        {
            header("Location: index.php");
            exit();
        }
        if (empty($_POST["submit"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValidLog("userauth", ["username", "password"]))
        {
            $secu->disconnect();
            return;
        }
        if (!$secu->hasAttempts($_POST["userauth"]["username"]))
        {
            $secu->disconnect();
            $secu->logger->footerLog("Vous ne pouvez plus faire d'essai, le compte est bloqué, merci de contacter un administrateur pour changer le mot de passe.", "danger");
            return;
        }
        $waitingTimeLeft = $secu->waitingTimeLeft();
        if ($waitingTimeLeft > 0)
        {
            $secu->disconnect();
            $secu->logger->footerLog("Attendez encore " . $waitingTimeLeft . " secondes avant de réessayer.");
            return;
        }
        if (!$secu->authUser($_POST["userauth"]))
        {
            $secu->disconnect();
            $secu->decrementAttempts($_POST["userauth"]["username"]);
            $secu->logger->footerLog("Nom d'utilisateur et/ou mot de passe invalide(s)");
            return;
        }

        header("Location: index.php");
    }

    auth();
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
            require_once("lib/navbar.php")
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
                    </form>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>