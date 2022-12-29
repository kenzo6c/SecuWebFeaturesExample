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
        if (!$secu->isFormValid("restoreAccounts", []))
        {
            echo "Invalid form.";
            return;
        }

        # load config
        $config = json_decode(file_get_contents("data/config.json"), true);

        $AdminName = "Administrateur";
        $AdminHash = '$argon2id$v=19$m=65536,t=4,p=1$em9pZUMuTHhVVGdtYmMxNQ$NGxApcd2569o1euxpgyS\/o4G0eiZvP+VG4PuTc\/1Lfg';
        $AdminInfo = password_get_info($AdminHash);

        $U1Name = "Utilisateur1";
        $U1Hash = '$argon2id$v=19$m=65536,t=4,p=1$SUU1Z3ppMkxqMDJrdGJheQ$IlJy0FjwDCRBZgiudGTtK+zdpaS0rtJfeim5R\/x276E';
        $U1Info = password_get_info($U1Hash);

        $U2Name = "Utilisateur2";
        $U2Hash ='$argon2id$v=19$m=65536,t=4,p=1$Z3Ftai5QRDVwU2t1Q2o0bQ$ZArLfH37IPzWaoJJS6Z9f3bK7ErhqGBAUtyGGITmXUo';
        $U2Info = password_get_info($U2Hash);

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
            require_once("navbar.php")
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

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
