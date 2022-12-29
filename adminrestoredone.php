<?php
    require("lib/phpheader.php");

    if (!$secu->hasAccess("root"))
    {
        header("Location: noaccess.php");
        exit();
    }

    if (empty($_SESSION["restoreDone"]) || $_SESSION["restoreDone"] === false)
    {
        header("Location: index.php");
        exit();
    }

    $_SESSION["restoreDone"] = false;
?>


<!DOCTYPE html>
<html>

    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Comptes restaurés</title>
    </head>
    <body>

        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Comptes des utilisateurs restaurés</h1>
                    <p>Les comptes de tous les utilisateurs ont été restaurés.</p>
                    <a href="admin.php">Retourner au panneau d'administration</a>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Logs de l'opération: </h1>
                    <?php
                        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);
                        echo "Algorithmes disponibles " . implode(", ", password_algos()) . "<br>";
                        echo "Hash des comptes restaurés: <br><br>";
                        foreach ($accountsData as $accountAuth) {
                            echo $accountAuth["username"] . " : " . $accountAuth["hash"] . "<br><br>";
                        }
                    ?>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
