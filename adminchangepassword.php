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
        if (!$secu->isFormValidLog("fchgepass", ["username", "newpassword"]))
        {
            return;
        }
        $user = $_POST["fchgepass"]["username"];
        $newpassword = $_POST["fchgepass"]["newpassword"];
        if (!$secu->checkIfUserExists($user))
        {
            $secu->logger->footerLog("Cet utilisateur n'existe pas");
            return;
        }
        if ($secu->userIsRoot($user))
        {
            $secu->logger->footerLog("Vous ne pouvez pas forcer le changement du mot de passe d'un admnistrateur.");
            return;
        }
        $weakness = $secu->passwordWeakness($newpassword);
        if ($weakness !== "None")
        {
            $secu->logger->footerLog("Le mot de passe est trop faible. Faiblesse: " . $weakness);
            return;
        }

        # --- Functionnal code ---
        $secu->changePassword($user, $newpassword, $config["hashAlgorithm"], true);
        $secu->logger->footerLog("Le mot de passe de \"" . $user . "\" a été changé.", "success");
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
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center text-danger">Changement de mot de passe forcé</h1>

                    <p>
                        <a href="admin.php">Retourner au panneau d'administration</a><br/>
                    </p>

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

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>