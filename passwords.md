
This file is just to test the app, it wouldn't exist in a real project.
Password are not supposed to be stored, only their hash is stored in the appropriate file: clients.json.
Also, in a real project, a SQL database would be used instead of a JSON file.

Passwords of the users :
- Administrateur: ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H
- Utilisateur 1: 2tC5gaJN8y2K7ASh7WFq
- Utilisateur 2: BonjourVJ30h27121990

Use the following code to generate the password:

```php
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

    file_put_contents("accounts.json", json_encode($users));
    $accountsData = json_decode(file_get_contents("accounts.json"), true);

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
