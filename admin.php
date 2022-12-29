<?php
    require("lib/phpheader.php");
    if (!$secu->hasAccess("root"))
    {
        header("Location: noaccess.php");
        exit();
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
                                </tr>
                                <?php
                            }?>
                        </tbody>
                    </table>

                    <p>
                        Remarque : le sel (salt) des mots de passe est contenu dans le hash avec l'utilisation de la fonction password_hash() de PHP.
                    </p>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
