<?php
    $passwords = [
        "Administrateur" => "ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H",
        "Utilisateur1" => "2tC5gaJN8y2K7ASh7WFq",
        "Utilisateur2" => "BonjourVJ30h27121990"
    ];

    $AdminName = "Administrateur";
    $AdminHash = password_hash($passwords[$AdminName], PASSWORD_ARGON2ID);
    $AdminInfo = password_get_info($AdminHash);

    $U1Name = "Utilisateur1";
    $U1Hash = password_hash($passwords[$U1Name], PASSWORD_ARGON2ID);
    $U1Info = password_get_info($U1Hash);

    $U2Name = "Utilisateur2";
    $U2Hash = password_hash($passwords[$U2Name], PASSWORD_ARGON2ID);
    $U2Info = password_get_info($U2Hash);

    $users = [
        $AdminName  => [
            "username" =>  $AdminName,
            "hash" => $AdminHash,
            "algoPHP" => $AdminInfo["algo"],
            "algoHuman" => $AdminInfo["algoName"],
            "access" => [],
            "is_root" => true
        ],
        $U1Name => [
            "username" =>  $U1Name,
            "hash" => $U1Hash,
            "algoPHP" => $AdminInfo["algo"],
            "algoHuman" => $AdminInfo["algoName"],
            "access" => ["clients_res", "user"],
            "is_root" => false
        ],
        $U2Name => [
            "username" => $U2Name,
            "hash" => $U2Hash,
            "algoPHP" => $AdminInfo["algo"],
            "algoHuman" => $AdminInfo["algoName"],
            "access" => ["clients_aff", "user"],
            "is_root" => false
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