# SecuWebsiteExample

## English
This project is a website implementing some security features. It is NOT a secure website! Only some parts are secured, and it was created only for educational purposes.

The informations present in this file are here for testing purposes, and wouldn't exist in a real project.
Also, in a real project, a more robust database would be used instead of a JSON file.

The salt is included in the hash with the password_hash function, see:
https://www.php.net/manual/en/faq.passwords.php#faq.password.storing-salts
Which means we don't need to store it separately!

Passwords of the users :
- Administrateur: ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H
- Utilisateur1: 2tC5gaJN8y2K7ASh7WFq
- Utilisateur2: BonjourVJ30h27121990

Use the code from adminrestoreaccounts.php to restore the default values of the account (password, attempts left, ...).
Paste in a file and run it in a browser to restore the json file "clients.json" with the default users and their hashes. You can also connect as an administrator and use the restore button in the admin panel.

## Français
Ce projet est un site web implémentant quelques fonctionnalités de sécurité. Ce n'est PAS un site web sécurisé ! Seules certaines parties sont sécurisées, le site a été créé uniquement à des fins éducatives.

Les informations présentes dans ce fichiers sont là uniquement pour des tests, et n'existeraient pas dans un projet réel.
De plus, dans un projet réel, une base de données plus robuste serait utilisée au lieu d'un fichier JSON.

Le sel (salt) est inclus dans le hash avec la fonction password_hash, voir:
https://www.php.net/manual/fr/faq.passwords.php#faq.password.storing-salts
Ce qui signifie que nous n'avons pas besoin de le stocker séparément !

Mots de passe des utilisateurs :
- Administrateur: ZMGRCZ#&\JymzJ>]Ap\X_G^(YeIOf[aY6'H
- Utilisateur1: 2tC5gaJN8y2K7ASh7WFq
- Utilisateur2: BonjourVJ30h27121990

Utilisez le code de adminrestoreaccounts.php pour restaurer les valeurs par défaut des comptes (mot de passe, tentatives restantes, ...).
Copiez le code dans un fichier et exécutez-le dans un navigateur pour restaurer le fichier json "clients.json" avec les utilisateurs par défaut et leurs hachages. Vous pouvez également vous connecter en tant qu'administrateur et utiliser le bouton de restauration dans le panneau d'administration.

## Structure du fichier accounts.json / accounts.json file structure:

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
