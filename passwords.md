
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

Use the code from adminrestoreaccounts.php to restore the default values of the account (password, attempts left, ...).


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
