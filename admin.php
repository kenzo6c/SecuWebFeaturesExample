<?php
    require("lib/phpheader.php");

    function adminPanel()
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
        if (empty($_POST["restoreAttempts"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValidLog("restoreAttempts", []))
        {
            return;
        }

        # --- Functionnal code ---
        $accounts = json_decode(file_get_contents("data/accounts.json"), true);
        foreach ($accounts as &$account)
        {
            $account["attempts_left"] = $config["maxAttemptsAccount"];
        }
        file_put_contents("data/accounts.json", json_encode($accounts));

        $secu->logger->printLog("Attempts left have been restored by \"" . $_SESSION["user"] . "\" for all users.");

    }

    adminPanel();

?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Panneau d'Administration</title>
    </head>
    <body>

        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">

            <div class="row">
                <div class="column">
                    <h1 class="text-center">Options d'Administrateur</h1>
                    <p>
                        <a href="adminlogs.php">Voir les logs (journaux)</a><br/>
                        <a href="adminconfigure.php">Configurer la politique de sécurité</a><br/>
                        <a href="adminchangepassword.php">Forcer le changement de mot de passe d'un utilisateur</a><br/>
                        <a href="adminrestoreaccounts.php" class="link">Restaurer tous les comptes</a><br/>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <h1 class="text-center">Liste des utilisateurs et infos</h1>

                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Nom d'utilisateur</th>
                                <th scope="col">Hash - (contient : l'algorithme, les paramètres, le sel, le hash pur)</th>
                                <th scole="col">Algorithme utilisé</th>
                                <th scole="col">Accès</th>
                                <th scole="col">Tentatives restantes</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            $accounts = json_decode(file_get_contents("data/accounts.json"), true);
                            foreach ($accounts as $username => $info) {
                                ?>
                                <tr>
                                    <td><?= $info["username"]?></td>
                                    <td><?= $info["hash"]?></td>
                                    <td><?= $info["algoHuman"]?></td>
                                    <td><?= $info["is_root"] ? "<span class=\"text-danger\">Administrateur (root)</span>" : implode(", ",$info["access"])?></td>
                                    <td><?= $info["attempts_left"] <= 0 ? "<span class=\"text-danger\">0</span>"  : $info["attempts_left"]?>/<?=$config["maxAttemptsAccount"]?></td>
                                </tr>
                                <?php
                            }?>
                        </tbody>
                    </table>

                    <p>
                        Remarque : le sel (salt) des mots de passe est contenu dans le hash avec l'utilisation de la fonction password_hash() de PHP.
                    </p>

                    <form action="#" method="post" name="restoreform">
                        <?php $secu->insertCSRFField();?>
                        <input type="submit" name="restoreAttempts" class=" btn btn-warning" value="Restaurer le nombre de tentatives"><br/><br/>
                    </form>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
