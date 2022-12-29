<?php
    require("lib/phpheader.php");

    function adminChangePassword()
    {
        # --- Global variables ---
        global $secu;
        global $config;

        # --- Guard Clauses ---
        if (!$secu->hasAccess("root"))
        {
            header("Location: noaccess.php");
            exit();
        }
        if (empty($_POST["submit"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValid("fchgepass", ["username", "newpassword"]))
        {
            echo "Invalid form.";
            return;
        }
        $user = $_POST["fchgepass"]["username"];
        $newpassword = $_POST["fchgepass"]["newpassword"];
        if (!$secu->checkIfUserExists($user))
        {
            echo "This user doesn't exist.";
            return;
        }
        if ($secu->userIsRoot($user))
        {
            echo "You can't force the password change of an administrator.";
            return;
        }
        $weakness = $secu->passwordWeakness($newpassword);
        if ($weakness !== "None")
        {
            echo "Password is not strong enough.";
            echo "Weakness: " . $weakness;
            return;
        }

        # --- Functionnal code ---
        $secu->changePassword($user, $newpassword, $config["hashAlgorithm"], true);
        echo "The Password of \"" . $user . "\" has been changed.";
    }

    adminChangePassword();

?>

<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>[FORCE] Changement mot de passe</title>
    </head>
    <body>

        <?php
            require_once("navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center text-danger">Changement de mot de passe forcé</h1>
                    <p>Entrez le nom de l'utilisateur pour qui vous voulez changer le mot de passe ainsi que son nouveau mot de passe.
                        <br/>Attention, vous ne pouvez pas changer le mot de passe d'un administrateur.
                        <br/>Le changement de mot de passe rétablira le nombre de tentatives de connexion restantes à la valeur par défaut.
                    </p>
                    <form action="#" method="post" name="authform">
                        <div>
                            <label for="username" class="form-label">Nom d'utilisateur :</label>
                            <input id ="username" type="text" name="fchgepass[username]" class="form-control" placeholder="Nom d'utilisateur" required>
                        </div>
                        <div>
                            <label for="newpassword" class="form-label">Nouveau mot de passe :</label>
                            <input id ="newpassword" type="password" name="fchgepass[newpassword]" class="form-control" placeholder="Nouveau mot de passe" required>
                        </div>
                        <div>
                            <?php $secu->insertCSRFField(); ?>
                        </div>
                        <div>
                            <br/>
                            <input type="submit" name="submit" class=" btn btn-danger" value="Forcer le changement de mot de passe">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>