<?php
    require("lib/phpheader.php");
    if (!$secu->hasAccess("root"))
    {
        header("Location: noaccess.php");
        exit();
    }

    $passwords = [
        "Administrateur" => "ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H",
        "Utilisateur1" => "2tC5gaJN8y2K7ASh7WFq",
        "Utilisateur2" => "BonjourVJ30h27121990"
    ];

    # load config
    $config = json_decode(file_get_contents("data/config.json"), true);

    $AdminName = "Administrateur";
    $AdminHash = password_hash($passwords[$AdminName], $config["hashAlgorithm"]);
    $AdminInfo = password_get_info($AdminHash);

    $U1Name = "Utilisateur1";
    $U1Hash = password_hash($passwords[$U1Name], $config["hashAlgorithm"]);
    $U1Info = password_get_info($U1Hash);

    $U2Name = "Utilisateur2";
    $U2Hash = password_hash($passwords[$U2Name], $config["hashAlgorithm"]);
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
    $accountsData = json_decode(file_get_contents("data/accounts.json"), true);
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
            require_once("navbar.php")
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
                        echo "Algorithmes disponibles " . implode(", ", password_algos()) . "<br>";
                        echo "Hash des comptes restaurés: <br><br>";
                        foreach ($accountsData as $accountAuth) {
                            echo $accountAuth["username"] . " : " . $passwords[$accountAuth["username"]] . " : " . $accountAuth["hash"] . " : " . $accountAuth["algoPHP"] . " : " . $accountAuth["algoHuman"] . "<br>";
                            if (password_verify($passwords[$accountAuth["username"]], $accountAuth["hash"]))
                            {
                                echo "-> Mot de passe OK.";
                            }
                            else
                            {
                                echo "-> /!\ Mot de passe NOK!";
                            }
                            echo "<br><br>";
                        }
                    ?>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
