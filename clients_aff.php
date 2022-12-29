<?php
    require("lib/phpheader.php");
    if (!$secu->hasAccess("clients_aff"))
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
        <title>Clients d'affaire</title>
    </head>
    <body>
        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Liste des clients d'affaire</h1>

                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom</th>
                                <th scole="col">Téléphone</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            $clients = json_decode(file_get_contents("data/clients_aff.json"), true);
                            foreach ($clients as $fullname => $info) {
                                ?>
                                <tr>
                                    <td><?= $info["Prénom"]?></td>
                                    <td><?= $info["Nom"]?></td>
                                    <td><?= $info["Téléphone"]?></td>
                                </tr>
                                <?php
                            }?>
                        </tbody>
                    </table>

                    <a href="index.php">Retourner à la page d'accueil</a>
                </div>
            </div>
        </div>
    </body>
</html>
