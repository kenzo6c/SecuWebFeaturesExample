<?php
    require("lib/phpheader.php");

    function restoreAccounts()
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
        if (empty($_POST["restoreAccounts"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValidLog("restoreAccounts", []))
        {
            return;
        }

        # load config
        $config = json_decode(file_get_contents("data/config.json"), true);
        $defaultAccounts = json_decode(file_get_contents("data/accountsDefault.json"), true);

        $AdminName = "Administrateur";
        $AdminHash = $defaultAccounts[$AdminName]["hash"];
        $AdminInfo = password_get_info($AdminHash);

        $U1Name = "Utilisateur1";
        $U1Hash = $defaultAccounts[$U1Name]["hash"];
        $U1Info = password_get_info($U1Hash);

        $U2Name = "Utilisateur2";
        $U2Hash = $defaultAccounts[$U2Name]["hash"];
        $U2Info = password_get_info($U2Hash);

        print_r($defaultAccounts[$AdminName]["hash"]);

        $users = [
            $AdminName  => [
                "username" =>  $AdminName,
                "hash" => $AdminHash,
                "algoPHP" => $AdminInfo["algo"],
                "algoHuman" => $AdminInfo["algoName"],
                "access" => [],
                "is_root" => true,
                "attempts_left" => $config["maxAttemptsAccount"]
            ],
            $U1Name => [
                "username" =>  $U1Name,
                "hash" => $U1Hash,
                "algoPHP" => $AdminInfo["algo"],
                "algoHuman" => $AdminInfo["algoName"],
                "access" => ["clients_res", "user"],
                "is_root" => false,
                "attempts_left" => $config["maxAttemptsAccount"]
            ],
            $U2Name => [
                "username" => $U2Name,
                "hash" => $U2Hash,
                "algoPHP" => $AdminInfo["algo"],
                "algoHuman" => $AdminInfo["algoName"],
                "access" => ["clients_aff", "user"],
                "is_root" => false,
                "attempts_left" => $config["maxAttemptsAccount"]
            ],
        ];

        file_put_contents("data/accounts.json", json_encode($users));

        $_SESSION["restoreDone"] = true;
        header("Location: adminrestoredone.php");
    }

    restoreAccounts();
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Restaurer tous les comptes</title>
    </head>
    <body>

        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Restaurer tous les comptes</h1>
                    <p>Cliquez ici pour restaurer tous les comptes. Attention, cette action est irréversible !</p>
                    <form action="#" method="post" name="restoreaccountsform">
                        <?php $secu->insertCSRFField();?>
                        <input type="submit" name="restoreAccounts" class=" btn btn-danger" value="Restaurer tous les comptes (ACTION IRRÉVERSIBLE)"><br/><br/>
                    </form>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
