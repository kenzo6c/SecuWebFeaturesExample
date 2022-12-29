<?php
    require("lib/phpheader.php");
    if (!$secu->hasAccess("root"))
    {
        header("Location: noaccess.php");
        exit();
    }

    if (!empty($_POST["newconfig"]))
    {
        print_r($_POST["newconfig"]);
    }
    else
    {
        echo "No new config";
    }

    if (!empty($_POST["submit"]))
    {
        if ($secu->isFormValid("newconfig", ["maxAttemptsSession", "maxAttemptsAccount", "hashAlgorithm", "CSRFTokenLength", "passwordminlength", "passwordmaxlength"]))
        {
            $newconfig = $_POST["newconfig"];

            $previous_max_attempts = $config["maxAttemptsAccount"];

            $config = [
                "maxAttemptsSession" => intval($newconfig["maxAttemptsSession"]),
                "maxAttemptsAccount" => intval($newconfig["maxAttemptsAccount"]),
                "hashAlgorithm" => $newconfig["hashAlgorithm"],
                "CSRFTokenLength" => intval($newconfig["CSRFTokenLength"]),
                "passwordminlength" => intval($newconfig["passwordminlength"]),
                "passwordmaxlength" => intval($newconfig["passwordmaxlength"]),
                "requireDigit" => isset($newconfig["requireDigit"]) && $newconfig["requireDigit"] === "1" ? true : false,
                "requireLetter" => isset($newconfig["requireLetter"]) && $newconfig["requireLetter"] === "1" ? true : false,
                "requireSymbol" => isset($newconfig["requireSymbol"]) && $newconfig["requireSymbol"] === "1" ? true : false
            ];
            file_put_contents("data/config.json", json_encode($config));

            $accounts = json_decode(file_get_contents("data/accounts.json"), true);
            foreach ($accounts as &$account)
            {
                if ($account["attempts_left"] > $config["maxAttemptsAccount"])
                {
                    $account["attempts_left"] = $config["maxAttemptsAccount"];
                    echo "yo" . $account["username"] . " - left:". $account["attempts_left"] . "yo" . "<br/>";
                }
            }
            print_r($accounts["Utilisateur1"]["attempts_left"]);
            file_put_contents("data/accounts.json", json_encode($accounts));
            echo "done!";
            echo "Configuration has been updated.";
        }
        else
        {
            echo "Invalid auth.";
        }
    }
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Configurer la politique de sécurité</title>
    </head>
    <body>

        <?php
            require_once("navbar.php")
        ?>

        <br/>
        <div class="container">

            <div class="row">
                <div class="column">
                    <form action="#" method="post" name="adminform">

                        <label for="maxAttemptsSession" class="form-label">Nombre maximal d'essais par session :</label>
                        <input type="number" name="newconfig[maxAttemptsSession]" id="maxAttemptsSession" class="form-control" value="<?= $config["maxAttemptsSession"]?>"><br/>


                        <label for="maxAttemptsAccount" class="form-label">Nombre maximal d'essais par compte :</label>
                        <input type="number" name="newconfig[maxAttemptsAccount]" id="maxAttemptsAccount" class="form-control" value="<?= $config["maxAttemptsAccount"]?>"><br/>

                        <label for="hashAlgorithm" class="form-label">Algorithme de hashage :</label>
                        <select name="newconfig[hashAlgorithm]" id="hashAlgorithm" class="form-select">
                            <option value= <?=PASSWORD_BCRYPT?> <?= $config["hashAlgorithm"] === PASSWORD_BCRYPT ? "selected" : ""?>>PASSWORD_BCRYPT</option>
                            <option value= <?=PASSWORD_ARGON2I?> <?= $config["hashAlgorithm"] === PASSWORD_ARGON2I ? "selected" : ""?>>PASSWORD_ARGON2I</option>
                            <option value= <?=PASSWORD_ARGON2ID?> <?= $config["hashAlgorithm"] === PASSWORD_ARGON2ID ? "selected" : ""?>>PASSWORD_ARGON2ID</option>
                        </select><br/>

                        <label for="CSRFTokenLength" class="form-label">Longueur du token CSRF :</label>
                        <input type="number" name="newconfig[CSRFTokenLength]" id="CSRFTokenLength" class="form-control" value="<?= $config["CSRFTokenLength"]?>"><br/>

                        <label for="passwordminlength" class="form-label">Longueur minimale du mot de passe :</label>
                        <input type="number" name="newconfig[passwordminlength]" id="passwordminlength" class="form-control" value="<?= $config["passwordminlength"]?>"><br/>

                        <label for="passwordmaxlength" class="form-label">Longueur maximale du mot de passe :</label>
                        <input type="number" name="newconfig[passwordmaxlength]" id="passwordmaxlength" class="form-control" value="<?= $config["passwordmaxlength"]?>"><br/>

                        <label for="requireDigit" class="form-check-label">Exiger un chiffre dans le mot de passe :</label>
                        <input type="checkbox" name="newconfig[requireDigit]" id="requireDigit" class="form-check-input" value="1" <?= $config["requireDigit"] ? "checked" : ""?>><br/>
                        <br/>

                        <label for="requireLetter" class="form-check-label">Exiger une lettre dans le mot de passe :</label>
                        <input type="checkbox" name="newconfig[requireLetter]" id="requireLetter" class="form-check-input" value="1" <?= $config["requireLetter"] ? "checked" : ""?>><br/>
                        <br/>

                        <label for="requireSymbol" class="form-check-label">Exiger un symbole dans le mot de passe :</label>
                        <input type="checkbox" name="newconfig[requireLetter]" id="requireSymbol" class="form-check-input" value="1" <?= $config["requireSymbol"] ? "checked" : ""?>><br/>
                        <br/>

                        <?php $secu->insertCSRFField(); ?>

                        <input type="submit" name="submit" class=" btn btn-warning" value="Mettre à jour la configuration"><br/><br/>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
