<?php
    $passwords = [
        "Administrateur" => "ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H",
        "Utilisateur1" => "2tC5gaJN8y2K7ASh7WFq",
        "Utilisateur2" => "BonjourVJ30h27121990"
    ];

    # load config
    $config = json_decode(file_get_contents("../data/config.json"), true);

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

    file_put_contents("../data/accounts.json", json_encode($users));
    $accountsData = json_decode(file_get_contents("../data/accounts.json"), true);

    echo "Available algorithms: " . implode(", ", password_algos()) . "<br><br>";
    foreach ($accountsData as $accountAuth) {
        echo $accountAuth["username"] . " : " . $passwords[$accountAuth["username"]] . " : " . $accountAuth["hash"] . " : " . $accountAuth["algoPHP"] . " : " . $accountAuth["algoHuman"] . "<br>";
        if (password_verify($passwords[$accountAuth["username"]], $accountAuth["hash"]))
        {
            echo "yes";
        }
        else
        {
            echo "no";
        }
        echo "<br><br>";
    }
?>