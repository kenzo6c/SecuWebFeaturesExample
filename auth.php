<?php
    require("lib/phpheader.php");

    function auth()
    {
        # --- Global variables ---
        global $secu;
        global $config;

        # --- Guard Clauses ---
        if (!empty($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
        {
            header("Location: index.php");
            exit();
        }
        if (empty($_POST["submit"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValid("userauth", ["username", "password"]))
        {
            $secu->disconnect();
            echo "Invalid form.";
            return;
        }
        if (!$secu->hasAttempts($_POST["userauth"]["username"]))
        {
            $secu->disconnect();
            echo "No attempts left, the account is locked, please contact an administrator for a password reset.";
            return;
        }
        $waitingTimeLeft = $secu->waitingTimeLeft();
        if ($waitingTimeLeft > 0)
        {
            $secu->disconnect();
            echo "Please wait " . $waitingTimeLeft . " seconds.";
            return;
        }
        if (!$secu->authUser($_POST["userauth"]))
        {
            $secu->disconnect();
            $secu->decrementAttempts($_POST["userauth"]["username"]);
            echo "Wrong username or password";
            return;
        }

        # --- Functionnal code ---
        print($_SESSION["user"]);
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
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>