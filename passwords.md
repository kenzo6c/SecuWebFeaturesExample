
This file is just to test the app, it wouldn't exist in a real project.
Password are not supposed to be stored, only their hash is stored in the appropriate file: clients.json.
Also, in a real project, a SQL database would be used instead of a JSON file.

Rq: The salt is included in the hash with the password_hash function, see:
https://www.php.net/manual/en/faq.passwords.php#faq.password.storing-salts
Which means we don't need to store it separately!

Passwords of the users :
- Administrateur: ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H
- Utilisateur1: 2tC5gaJN8y2K7ASh7WFq
- Utilisateur2: BonjourVJ30h27121990

Use the following code to generate the password:

```php
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
            "algoHuman" => $AdminInfo["algoName"]
        ],
        $U1Name => [
            "username" =>  $U1Name,
            "hash" => $U1Hash,
            "algoPHP" => $AdminInfo["algoName"],
            "algoHuman" => "Argon2id"
        ],
        $U2Name => [
            "username" => $U2Name,
            "hash" => $U2Hash,
            "algoPHP" => $AdminInfo["algoName"],
            "algoHuman" => "Argon2id"
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
```

Paste in a file and run it in a browser to create the json file "clients.json" with the default users and their hashes.
The JSON file should look like this:

```json
{
    "User": {
        "username": "myname",
        "hash": "myhash",
        "algoPHP": "PHP Variable of the hash algorithm",
        "algoHuman": "Human readable name of the hash algorithm"
    }
}
```
