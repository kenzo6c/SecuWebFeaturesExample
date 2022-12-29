<?php
    require("lib/phpheader.php");
    if (!$secu->hasAccess("root"))
    {
        header("Location: noaccess.php");
        exit();
    }
    # Confiugre the $config array with the new values coming from the POST request
    if (!empty($_POST["submit"]))
    {
        if ($secu->isFormValid("newconfig", ["maxNbrOfAttempts", "hashAlgorithm", "CSRFTokenLength", "passwordminlength", "passwordmaxlength", "requireDigit", "requireLetter", "requireSymbol"]))
        {
            $newconfig = $_POST["newconfig"];

            # create the $config array with the new values
            $config = [
                "maxNbrOfAttempts" => intval($newconfig["maxNbrOfAttempts"]),
                "hashAlgorithm" => $newconfig["hashAlgorithm"],
                "CSRFTokenLength" => intval($newconfig["CSRFTokenLength"]),
                "passwordminlength" => intval($newconfig["passwordminlength"]),
                "passwordmaxlength" => intval($newconfig["passwordmaxlength"]),
                "requireDigit" => $newconfig["requireDigit"] === "1" ? true : false,
                "requireLetter" => $newconfig["requireLetter"] === "1" ? true : false,
                "requireSymbol" => $newconfig["requireSymbol"] === "1" ? true : false
            ];
            file_put_contents("data/config.json", json_encode($config));

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
                    <form action="admin.php" method="post">
                        <label for="maxNbrOfAttempts" class="form-label">Nombre maximal d'essais :</label>
                        <input type="number" name="newconfig[maxNbrOfAttempts]" id="maxNbrOfAttempts" class="form-control" value="<?= $config["maxNbrOfAttempts"]?>"><br/>

                        <label for="hashAlgorithm" class="form-label">Algorithme de hashage :</label>
                        <select name="newconfig[hashAlgorithm]" id="hashAlgorithm" class="form-select">
                            <option value="PASSWORD_BCRYPT" <?= $config["hashAlgorithm"] === PASSWORD_BCRYPT ? "selected" : ""?>>PASSWORD_BCRYPT</option>
                            <option value="PASSWORD_ARGON2I" <?= $config["hashAlgorithm"] === PASSWORD_ARGON2I ? "selected" : ""?>>PASSWORD_ARGON2I</option>
                            <option value="PASSWORD_ARGON2ID" <?= $config["hashAlgorithm"] === PASSWORD_ARGON2ID ? "selected" : ""?>>PASSWORD_ARGON2ID</option>
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
                        <input type="checkbox" name="newconfig[requireSymbol]" id="requireSymbol" class="form-check-input" value="1" <?= $config["requireSymbol"] ? "checked" : ""?>><br/>
                        <br/>

                        <button type="submit" class="btn btn-warning">Mettre à jour la configuration</button><br/><br/>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
