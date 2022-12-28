<?php
    $passwords = [
        "Administrateur" => "ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H",
        "Utilisateur 1" => "2tC5gaJN8y2K7ASh7WFq",
        "Utilisateur 2" => "BonjourVJ30h27121990"
    ];

    $users = [
        "Administrateur" => [
            "username" => "Administrateur",
            "hash" => password_hash($passwords["Administrateur"], PASSWORD_ARGON2ID),
            "algoPHP" => PASSWORD_ARGON2ID,
            "algoHuman" => "Argon2id"
        ],
        "Utilisateur 1" => [
            "username" => "Utilisateur 1",
            "hash" => password_hash($passwords["Utilisateur 1"], PASSWORD_BCRYPT),
            "algoPHP" => PASSWORD_BCRYPT,
            "algoHuman" => "Bcrypt"
        ],
        "Utilisateur 2" => [
            "username" => "Utilisateur 2",
            "hash" => password_hash($passwords["Utilisateur 2"], PASSWORD_ARGON2ID),
            "algoPHP" => PASSWORD_ARGON2ID,
            "algoHuman" => "Argon2id"
        ]
    ];

    file_put_contents("data/accounts.json", json_encode($users));
    $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

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