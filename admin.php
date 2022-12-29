<?php
    require("lib/phpheader.php");
    if (!$secu->hasAccess("root"))
    {
        header("Location: noaccess.php");
        exit();
    }


    if (!empty($_POST["restoreAttempts"]))
    {
        if ($secu->isFormValid("restoreAttempts", []))
        {
            $accounts = json_decode(file_get_contents("data/accounts.json"), true);
            foreach ($accounts as &$account)
            {
                $account["attempts_left"] = $config["maxAttemptsAccount"];
            }
            file_put_contents("data/accounts.json", json_encode($accounts));
        }
        else
        {
            echo "Invalid auth.";
        }
    }
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Panneau d'administration</title>
    </head>
    <body>

        <?php
            require_once("navbar.php")
        ?>

        <br/>
        <div class="container">

            <div class="row">
                <div class="column">
                    <p>
                        <a href="adminconfigure.php">Configurer la politique de sécurité</a><br/>
                        <a href="adminchangepassword.php">Forcer le changement de mot de passe d'un utilisateur</a><br/>
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

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
